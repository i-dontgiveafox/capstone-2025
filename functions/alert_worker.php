<?php
// alert_worker.php
// This script can be run from CLI (cron) or via web to process alerts and send deduplicated emails.

// Load .env file first (before any requires)
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            // Remove surrounding quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            putenv("$key=$value");
        }
    }
}

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/send_alert_email.php';

// CLI-friendly
$isCli = (php_sapi_name() === 'cli');

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log('DB connection failed in alert_worker');
    if ($isCli) echo "DB connection failed\n";
    exit(1);
}

// Ensure table exists
$createSql = "CREATE TABLE IF NOT EXISTS alert_states (
  id INT AUTO_INCREMENT PRIMARY KEY,
  alert_type VARCHAR(50) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 0,
  last_triggered TIMESTAMP NULL,
  email_sent TINYINT(1) NOT NULL DEFAULT 0,
  email_sent_at TIMESTAMP NULL,
  UNIQUE KEY uniq_alert_type (alert_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
$conn->query($createSql);
// Ensure email_audit table exists
$createAuditSql = "CREATE TABLE IF NOT EXISTS email_audit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alert_type VARCHAR(50) DEFAULT NULL,
    recipient VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
$conn->query($createAuditSql);

// Determine current alerts (same logic as fetch_notifications)
$alerts = [];

// Gas threshold
$gasThreshold = 6.0;
$thrRes = $conn->query("SELECT value FROM co2_threshold WHERE id = 1 LIMIT 1");
if ($thrRes && $thrRes->num_rows > 0) {
    $r = $thrRes->fetch_assoc();
    $gasThreshold = floatval($r['value']);
}

$sqlGas = "SELECT * FROM gas_data WHERE gas_percent >= $gasThreshold ORDER BY gas_id DESC LIMIT 10";
$resGas = $conn->query($sqlGas);
if ($resGas) {
    while ($row = $resGas->fetch_assoc()) {
        $alerts[] = [
            'type' => 'gas',
            'message' => 'Your Soil is ready to harvest! (' . $row['gas_percent'] . '%)',
            'time' => date("g:i A", strtotime($row['gas_timestamp'])),
            'raw_time' => strtotime($row['gas_timestamp'])
        ];
    }
}

// Water alerts
$sqlWater = "SELECT * FROM water_level WHERE status = 'LOW' ORDER BY id DESC LIMIT 10";
$resWater = $conn->query($sqlWater);
if ($resWater) {
    while ($row = $resWater->fetch_assoc()) {
        $alerts[] = [
            'type' => 'water',
            'message' => 'Water Level is Low!',
            'time' => date("g:i A", strtotime($row['timestamp'])),
            'raw_time' => strtotime($row['timestamp'])
        ];
    }
}

// Sort and slice
usort($alerts, function($a, $b){ return $b['raw_time'] - $a['raw_time']; });
$alerts = array_slice($alerts, 0, 10);

// Build type presence
$types_present = [];
foreach ($alerts as $a) $types_present[$a['type']] = true;

// Fetch the user's recovery_email from DB (send alerts there instead of generic ALERT_EMAIL)
$recipientEmail = getenv('ALERT_EMAIL') ?: getenv('MAIL_FROM') ?: 'no-reply@vermicast.com';
$userRes = $conn->query("SELECT recovery_email FROM users WHERE recovery_email IS NOT NULL AND recovery_email != '' LIMIT 1");
if ($userRes && $userRes->num_rows > 0) {
    $userRow = $userRes->fetch_assoc();
    if (!empty($userRow['recovery_email'])) {
        $recipientEmail = $userRow['recovery_email'];
    }
}

$checkStmt = $conn->prepare('SELECT id, is_active, email_sent FROM alert_states WHERE alert_type = ? LIMIT 1');
$insertStmt = $conn->prepare('INSERT INTO alert_states (alert_type, is_active, last_triggered, email_sent, email_sent_at) VALUES (?, ?, NOW(), ?, NOW())');
$updateActiveStmt = $conn->prepare('UPDATE alert_states SET is_active = ?, last_triggered = NOW() WHERE id = ?');
$markEmailedStmt = $conn->prepare('UPDATE alert_states SET email_sent = 1, email_sent_at = NOW() WHERE id = ?');
$resetStmt = $conn->prepare('UPDATE alert_states SET is_active = 0, email_sent = 0 WHERE id = ?');

$alertTypes = ['gas', 'water'];
foreach ($alertTypes as $type) {
    $present = isset($types_present[$type]);
    $checkStmt->bind_param('s', $type);
    $checkStmt->execute();
    $res = $checkStmt->get_result();
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $id = $row['id'];
        $is_active = (int)$row['is_active'];
        $email_sent = (int)$row['email_sent'];

        if ($present) {
            if (!$is_active) {
                $one = 1;
                $updateActiveStmt->bind_param('ii', $one, $id);
                $updateActiveStmt->execute();
                $is_active = 1;
            }
            if ($is_active && !$email_sent) {
                $subject = ucfirst($type) . ' Alert from VermiCare';
                $body = '<p>An alert was detected on the system:</p>';
                foreach ($alerts as $a) {
                    if ($a['type'] === $type) {
                        $body .= '<p><strong>' . htmlspecialchars($a['message']) . '</strong> at ' . htmlspecialchars($a['time']) . '</p>';
                        break;
                    }
                }
                $sent = send_alert_email($recipientEmail, $subject, $body, $type);
                if ($sent) {
                    $markEmailedStmt->bind_param('i', $id);
                    $markEmailedStmt->execute();
                    if ($isCli) echo "Email sent for $type\n";
                }
            }
        } else {
            if ($is_active) {
                $resetStmt->bind_param('i', $id);
                $resetStmt->execute();
                if ($isCli) echo "Reset state for $type\n";
            }
        }
    } else {
        if ($present) {
            $one = 1; $zero = 0;
            $insertStmt->bind_param('sii', $type, $one, $zero);
            $insertStmt->execute();
            $newId = $conn->insert_id;
            $subject = ucfirst($type) . ' Alert from VermiCare';
            $body = '<p>An alert was detected on the system:</p>';
            foreach ($alerts as $a) {
                if ($a['type'] === $type) {
                    $body .= '<p><strong>' . htmlspecialchars($a['message']) . '</strong> at ' . htmlspecialchars($a['time']) . '</p>';
                    break;
                }
            }
            $sent = send_alert_email($recipientEmail, $subject, $body, $type);
            if ($sent) {
                $markEmailedStmt->bind_param('i', $newId);
                $markEmailedStmt->execute();
                if ($isCli) echo "Email sent for $type (new row)\n";
            }
        }
    }
}

// close
$checkStmt->close(); $insertStmt->close(); $updateActiveStmt->close(); $markEmailedStmt->close(); $resetStmt->close();
$conn->close();

if ($isCli) echo "Done.\n";

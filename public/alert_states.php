<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php');
    exit;
}

require_once __DIR__ . '/../config/db.php';
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die('DB connection failed');

// Handle reset action
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset_id'])) {
        $id = intval($_POST['reset_id']);
        $stmt = $conn->prepare('UPDATE alert_states SET is_active = 0, email_sent = 0 WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        $message = 'Alert state reset.';
    }
    if (isset($_POST['reset_all'])) {
        $conn->query('UPDATE alert_states SET is_active = 0, email_sent = 0');
        $message = 'All alert states reset.';
    }
}

// Fetch states
$result = $conn->query('SELECT * FROM alert_states ORDER BY last_triggered DESC, alert_type ASC');
$rows = [];
if ($result) {
    while ($r = $result->fetch_assoc()) $rows[] = $r;
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Alert States</title>
    <link rel="stylesheet" href="../index.css">
    <style>.container{max-width:900px;margin:40px auto;padding:20px;background:#fff;border-radius:8px} table{width:100%;border-collapse:collapse} th,td{padding:10px;border-bottom:1px solid #eee;text-align:left} .btn{padding:8px 12px;border-radius:6px;border:1px solid #ddd;background:#f8fafc} .btn-danger{background:#ef4444;color:#fff;border-color:#ef4444}</style>
</head>
<body class="bg-gradient-to-br from-[#CCEBD5]/90 to-[#B0CFCF]/90 min-h-screen">
<?php include __DIR__ . '/../includes/navBar.php'; ?>
<main class="container">
    <h1 style="margin:0 0 16px">Alert States</h1>
    <?php if (!empty($message)): ?>
        <div style="padding:10px;background:#e6ffed;border:1px solid #b7f5c6;border-radius:6px;margin-bottom:12px"><?=htmlspecialchars($message)?></div>
    <?php endif; ?>
    <form method="post" style="margin-bottom:12px">
        <button type="submit" name="reset_all" class="btn">Reset All</button>
    </form>
    <table>
        <thead>
            <tr><th>ID</th><th>Type</th><th>Is Active</th><th>Email Sent</th><th>Last Triggered</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php if (empty($rows)): ?>
                <tr><td colspan="6">No alert states found.</td></tr>
            <?php else: ?>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?=htmlspecialchars($r['id'])?></td>
                        <td><?=htmlspecialchars($r['alert_type'])?></td>
                        <td><?= $r['is_active'] ? 'Yes' : 'No' ?></td>
                        <td><?= $r['email_sent'] ? 'Yes' : 'No' ?></td>
                        <td><?=htmlspecialchars($r['last_triggered'])?></td>
                        <td>
                            <form method="post" style="display:inline">
                                <input type="hidden" name="reset_id" value="<?=htmlspecialchars($r['id'])?>">
                                <button type="submit" class="btn">Reset</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>

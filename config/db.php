<?php
// Load DB credentials from environment (.env) to keep secrets out of the repo.
// If vlucas/phpdotenv is installed (vendor/autoload.php present), it will be used.

// Try to load Composer autoload to enable phpdotenv if available.
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
	require_once __DIR__ . '/../vendor/autoload.php';
	if (class_exists('\Dotenv\Dotenv')) {
		try {
			\Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
		} catch (Exception $e) {
			// ignore; we'll fallback to getenv/$_ENV
		}
	}
}

// Read from environment variables (.env) or server environment.
// Leave empty or null if not set so the connection will fail loudly in dev.
$servername = getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? null);
$username   = getenv('DB_USER') ?: ($_ENV['DB_USER'] ?? null);
$password   = getenv('DB_PASS') ?: ($_ENV['DB_PASS'] ?? null);
$dbname     = getenv('DB_NAME') ?: ($_ENV['DB_NAME'] ?? null);

// Optional: provide helpful error if variables are missing when running via CLI/web
if (php_sapi_name() !== 'cli') {
	if (empty($servername) || empty($username) || $dbname === null) {
		error_log('Database configuration not found in environment. Please create a .env file or set DB_* environment variables.');
	}
}

?>

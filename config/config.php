<?php
function loadEnv($path) {
    if (!file_exists($path)) {
        throw new Exception('.env file not found');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

loadEnv(__DIR__ . '/../.env');

define('DB_HOST', getenv('DB_HOST'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('DB_CHARSET', getenv('DB_CHARSET'));

define('SITE_NAME', getenv('SITE_NAME'));
define('SITE_URL', getenv('SITE_URL'));
define('ADMIN_EMAIL', getenv('ADMIN_EMAIL'));

define('WHATSAPP_NUMBER', getenv('WHATSAPP_NUMBER'));
define('GOOGLE_MAP_EMBED', getenv('GOOGLE_MAP_EMBED'));

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

date_default_timezone_set('Asia/Kolkata');

session_start();

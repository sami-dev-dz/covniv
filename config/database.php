<?php
// config/database.php

// Simple function to load .env variables manually since we don't have composer/vlucas/phpdotenv installed by default.
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
        putenv(sprintf('%s=%s', trim($name), trim($value)));
    }
}

// Load the .env file from the root directory
loadEnv(dirname(__DIR__) . '/.env');

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            $host = getenv('DB_HOST') ?: 'localhost';
            $dbName = getenv('DB_DATABASE') ?: 'uniride';
            $username = getenv('DB_USERNAME') ?: 'root';
            $password = getenv('DB_PASSWORD') ?: '';

            try {
                // Using PDO for better security and modern syntax, but since the original used mysqli, we'll provide PDO here or stick to mysqli?
                // The original code used mysqli: $conn = mysqli_connect($servername, $username, $password, $dbname);
                // I will use PDO for the MVC approach as it's the professional standard.
                self::$connection = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Log error to logs/ directory instead of showing to user directly
                error_log("Connection error: " . $e->getMessage(), 3, dirname(__DIR__) . '/logs/error.log');
                die("A database error occurred. Please try again later.");
            }
        }
        return self::$connection;
    }
}

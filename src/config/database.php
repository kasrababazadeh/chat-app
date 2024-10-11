<?php

require __DIR__ . '/../../vendor/autoload.php';
class Database {
    public static function connect() {
        // Load environment variables
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Connect to SQLite database
        $dbPath = $_ENV['DB_PATH'];
        $pdo = new \PDO("sqlite:$dbPath");
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}

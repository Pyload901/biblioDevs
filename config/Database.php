<?php
class Database {
    public static function Connect() : PDO | null {
        try {
            $DB_HOST = $_ENV["DB_HOST"];
            $DB_DATABASE = $_ENV["DB_DATABASE"];
            $DB_USER= $_ENV["DB_USER"];
            $DB_PASSWORD = $_ENV["DB_PASSWORD"];
            $DB_PORT = isset($_ENV["DB_PORT"]) ? $_ENV["DB_PORT"] : 3306;
            return new PDO(
                "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_DATABASE",
                $DB_USER,
                $DB_PASSWORD
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
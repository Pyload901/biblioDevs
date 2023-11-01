<?php
class Database {
    public static function Connect() : PDO | null {
        try {
            $DB_HOST = $_ENV["DB_HOST"];
            $DB_DATABASE = $_ENV["DB_DATABASE"];
            $DB_USER= $_ENV["DB_USER"];
            $DB_PASSWORD = $_ENV["DB_PASSWORD"];
            return new PDO(
                "mysql:host=$DB_HOST;dbname=$DB_DATABASE",
                $DB_USER,
                $DB_PASSWORD
            );
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
}
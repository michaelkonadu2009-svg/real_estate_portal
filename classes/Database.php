<?php
// Loads the database configuration constants
require_once __DIR__ . '/../config/config.php';

// This class handles the main database connection
class Database {
    private ?PDO $conn = null;

    // Connects to the MySQL database
    public function connect(): PDO {

        // Only creates a new connection if one does not already exist
        if ($this->conn === null) {
            try {
                // Creates the database connection string
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";

                // Creates the PDO database connection
                $this->conn = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                // Stops the page if the database connection fails
                die("Database connection failed: " . $e->getMessage());
            }
        }

        // Returns the database connection
        return $this->conn;
    }
}
?>

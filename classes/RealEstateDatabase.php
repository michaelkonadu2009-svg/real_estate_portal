<?php
// Loads the main Database class file
require_once __DIR__ . '/Database.php';

// This class holds all database functions for the real estate portal
class RealEstateDatabase {
    private PDO $conn;

    // Creates the database connection when the class is used
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Adds a new user to the Users table
    public function addUser(string $userName, string $contactInfo, string $passwordHash, string $userType): bool {
        // Inserts a new user using a prepared statement
        $sql = "INSERT INTO Users (userName, contactInfo, passwordHash, userType)
                VALUES (:userName, :contactInfo, :passwordHash, :userType)";

        // Prepares the SQL statement
        $stmt = $this->conn->prepare($sql);

        // Runs the insert and returns true if successful
        return $stmt->execute([
            ':userName' => $userName,
            ':contactInfo' => $contactInfo,
            ':passwordHash' => $passwordHash,
            ':userType' => $userType
        ]);
    }

    // Finds one user by their username
    public function getUserByUsername(string $userName) {
        // Selects one user from the Users table
        $sql = "SELECT * FROM Users WHERE userName = :userName LIMIT 1";

        // Prepares and runs the query
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userName' => $userName]);

        // Returns the user record
        return $stmt->fetch();
    }

    // Adds a new property listing to the Properties table
    public function addProperty(string $title, string $propertyType, string $address, string $city, float $price, string $status, int $agentId): bool {
        // Inserts property information into the database
        $sql = "INSERT INTO Properties (title, propertyType, address, city, price, status, agentId)
        VALUES (:title, :propertyType, :address, :city, :price, :status, :agentId)";

        // Prepares the SQL statement
        $stmt = $this->conn->prepare($sql);

        // Runs the insert and returns true if successful
        return $stmt->execute([
            ':title' => $title,
            ':propertyType' => $propertyType,
            ':address' => $address,
            ':city' => $city,
            ':price' => $price,
            ':status' => $status,
            ':agentId' => $agentId
        ]);
    }

    // Gets all property listings with the agent name
    public function getAllProperties(): array {
        // Selects properties and joins them with the agent user account
        $sql = "SELECT p.*, u.userName AS agentName
                FROM Properties p
                JOIN Users u ON p.agentId = u.userId
                ORDER BY p.propertyId DESC";

        // Runs the query
        $stmt = $this->conn->query($sql);

        // Returns all property records
        return $stmt->fetchAll();
    }

    // Gets one property by its property ID
    public function getPropertyById(int $propertyId) {
        // Selects one property and its agent name
        $sql = "SELECT p.*, u.userName AS agentName
                FROM Properties p
                JOIN Users u ON p.agentId = u.userId
                WHERE p.propertyId = :propertyId";

        // Prepares and runs the query
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':propertyId' => $propertyId]);

        // Returns one property record
        return $stmt->fetch();
    }

    // Adds a new inquiry to the Inquiries table
    public function addInquiry(int $userId, int $propertyId, string $message): bool {
        // Inserts the user's inquiry message with the current date
        $sql = "INSERT INTO inquiries (userId, propertyId, message, inquiryDate)
                VALUES (:userId, :propertyId, :message, NOW())";

        // Prepares the SQL statement
        $stmt = $this->conn->prepare($sql);

        // Runs the insert and returns true if successful
        return $stmt->execute([
            ':userId' => $userId,
            ':propertyId' => $propertyId,
            ':message' => $message
        ]);
    }

    // Gives access to the database connection
    public function getConnection() {
        return $this->conn;
    }

    // Gets one user's information by user ID
    public function getUserDetails(int $userId) {
        // Selects the user from the Users table
        $sql = "SELECT * FROM Users WHERE userId = :userId";

        // Prepares and runs the query
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);

        // Returns the user record
        return $stmt->fetch();
    }

    // Gets properties based on city
    public function getPropertiesByCity(string $city): array {
        // Selects properties where the city matches
        $sql = "SELECT * FROM Properties WHERE city = :city";

        // Prepares and runs the query
        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':city' => $city
        ]);

        // Returns all matching properties
        return $stmt->fetchAll();
    }
}
?>

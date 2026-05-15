<?php
// Starts the session so the page can access logged-in user data
session_start();

// Loads the database class
require_once 'classes/RealEstateDatabase.php';

// Checks if the user is logged in
if (!isset($_SESSION['user'])) {
    die("You must be logged in.");
}

// Creates a database object
$db = new RealEstateDatabase();

// Gets the logged-in user's ID from the session
$userId = $_SESSION['user']['userId'];

// Gets the property ID from the URL
$propertyId = isset($_GET['propertyId'])
    ? (int)$_GET['propertyId']
    : 0;

// Gets the database connection
$conn = $db->getConnection();

// SQL statement to save the property as a favorite
$sql = "INSERT INTO favorites (userId, propertyId, savedDate)
        VALUES (:userId, :propertyId, NOW())";

// Prepares the SQL statement
$stmt = $conn->prepare($sql);

// Runs the SQL statement with the user ID and property ID
$stmt->execute([
    ':userId' => $userId,
    ':propertyId' => $propertyId
]);

// Sends the user back to the property details page
header("Location: property_details.php?id=" . $propertyId);
exit;
?>

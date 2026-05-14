<?php
session_start();
require_once 'classes/RealEstateDatabase.php';

if (!isset($_SESSION['user'])) {
    die("You must be logged in.");
}

$db = new RealEstateDatabase();

$userId = $_SESSION['user']['userId'];

$propertyId = isset($_GET['propertyId'])
    ? (int)$_GET['propertyId']
    : 0;

$conn = $db->getConnection();

$sql = "INSERT INTO favorites (userId, propertyId, savedDate)
        VALUES (:userId, :propertyId, NOW())";

$stmt = $conn->prepare($sql);

$stmt->execute([
    ':userId' => $userId,
    ':propertyId' => $propertyId
]);

header("Location: property_details.php?id=" . $propertyId);
exit;
?>
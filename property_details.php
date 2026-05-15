<?php
// Loads the database class
require_once 'classes/RealEstateDatabase.php';

// Creates a database object
$db = new RealEstateDatabase();

// Gets the property ID from the URL
$propertyId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Gets one property from the database using the property ID
$property = $db->getPropertyById($propertyId);
?>

<?php include 'includes/header.php'; ?>

<h2>Property Details</h2>

<?php if (!$property): ?>
    <?php // Shows an error if the property does not exist ?>
    <p class="error">Property not found.</p>
<?php else: ?>
    <div class="card">
        <h3><?= htmlspecialchars($property['title']) ?></h3>

        <p><strong>Type:</strong> <?= htmlspecialchars($property['propertyType']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($property['address']) ?></p>
        <p><strong>City:</strong> <?= htmlspecialchars($property['city']) ?></p>
        <p><strong>Price:</strong> $<?= htmlspecialchars($property['price']) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($property['status']) ?></p>
        <p><strong>Agent:</strong> <?= htmlspecialchars($property['agentName']) ?></p>
    </div>

    <?php // Shows inquiry and favorite links only for buyers and renters ?>
    <?php if (isset($_SESSION['user']) && in_array($_SESSION['user']['userType'], ['buyer', 'renter'], true)): ?>
        <a href="submit_inquiry.php?propertyId=<?= (int)$property['propertyId'] ?>">Submit Inquiry</a>

        <br><br>

        <a href="favorite_property.php?propertyId=<?= (int)$property['propertyId'] ?>">Add to Favorites</a>
    <?php endif; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

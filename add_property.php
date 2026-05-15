<?php
// Loads the config file, authentication file, and database class
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

// Only allows agents to access this page
requireRole(['agent']);

// Stores success or error messages
$message = '';

// Checks if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Creates a database object
    $db = new RealEstateDatabase();

    // Gets form input values and removes extra spaces
    $title = trim($_POST['title'] ?? '');
    $propertyType = trim($_POST['propertyType'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');

    // Converts price input into a number
    $price = (float)($_POST['price'] ?? 0);

    // Gets the property status, or sets it to available by default
    $status = $_POST['status'] ?? 'available';

    // Gets the logged-in agent's user ID from the session
    $agentId = (int)$_SESSION['user']['userId'];

    // Makes sure required fields are filled out
    if ($title && $propertyType && $address && $city && $price > 0) {
        try {
            // Inserts the new property into the database
            $db->addProperty($title, $propertyType, $address, $city, $price, $status, $agentId);

            // Shows success message
            $message = 'Property added successfully.';
        } catch (Throwable $e) {
            // Shows error message if something goes wrong
            $message = 'Error: ' . $e->getMessage();
        }
    } else {
        // Shows message if the form is incomplete
        $message = 'Please complete all required fields.';
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Add Property</h2>

<?php if ($message): ?>
    <!-- Displays success or error message -->
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!-- Form used by agents to add a property -->
<form method="POST">
    <label>Title</label>
    <input type="text" name="title" required>

    <label>Property Type</label>
    <input type="text" name="propertyType" placeholder="Apartment, House, Condo..." required>

    <label>Address</label>
    <input type="text" name="address" required>

    <label>City</label>
    <input type="text" name="city" required>

    <label>Price</label>
    <input type="number" step="0.01" name="price" required>

    <label>Status</label>
    <select name="status">
        <option value="available">available</option>
        <option value="sold">sold</option>
        <option value="rented">rented</option>
    </select>

    <!-- Submits the form -->
    <button type="submit">Add Property</button>
</form>

<?php include 'includes/footer.php'; ?>

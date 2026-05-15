<?php
// Loads config, authentication functions, and database class
require_once 'config/config.php';
require_once 'includes/auth.php';
require_once 'classes/RealEstateDatabase.php';

// Only allows buyers and renters to access this page
requireRole(['buyer', 'renter']);

// Creates a database object
$db = new RealEstateDatabase();

// Stores success or error messages
$message = '';

// Gets the property ID from the URL or from the form
$propertyId = isset($_GET['propertyId']) ? (int)$_GET['propertyId'] : (int)($_POST['propertyId'] ?? 0);

// Checks if the inquiry form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Gets the logged-in user's ID from the session
    $userId = (int)$_SESSION['user']['userId'];

    // Gets the inquiry message from the form
    $messageText = trim($_POST['message'] ?? '');

    // Makes sure the property ID and message are valid
    if ($propertyId > 0 && $messageText !== '') {
        try {
            // Saves the inquiry into the database
            $db->addInquiry($userId, $propertyId, $messageText);

            // Shows success message
            $message = 'Inquiry submitted successfully.';
        } catch (Throwable $e) {
            // Shows error message if something goes wrong
            $message = 'Error: ' . $e->getMessage();
        }
    } else {
        // Shows message if the inquiry is missing information
        $message = 'Please enter a message.';
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Submit Inquiry</h2>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <!-- Keeps track of which property the inquiry belongs to -->
    <input type="hidden" name="propertyId" value="<?= (int)$propertyId ?>">

    <label>Message</label>
    <textarea name="message" rows="6" required></textarea>

    <button type="submit">Send Inquiry</button>
</form>

<?php include 'includes/footer.php'; ?>

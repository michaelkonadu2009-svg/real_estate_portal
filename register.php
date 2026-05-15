<?php
// Loads the database class
require_once 'classes/RealEstateDatabase.php';

// Stores success or error messages
$message = '';

// Checks if the registration form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Creates a database object
    $db = new RealEstateDatabase();

    // Gets form input values and removes extra spaces
    $userName = trim($_POST['userName'] ?? '');
    $contactInfo = trim($_POST['contactInfo'] ?? '');

    // Gets the password and selected user role
    $password = $_POST['password'] ?? '';
    $userType = $_POST['userType'] ?? '';

    // Makes sure all fields are filled out
    if ($userName && $contactInfo && $password && $userType) {

        // Hashes the password before saving it to the database
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            // Adds the new user to the database
            $db->addUser($userName, $contactInfo, $passwordHash, $userType);

            // Shows success message
            $message = 'Registration successful. You may now log in.';
        } catch (Throwable $e) {
            // Shows error message if registration fails
            $message = 'Error: ' . $e->getMessage();
        }
    } else {
        // Shows message if any field is missing
        $message = 'Please fill in all fields.';
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Register</h2>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Username</label>
    <input type="text" name="userName" required>

    <label>Contact Info</label>
    <input type="text" name="contactInfo" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <label>User Type</label>
    <select name="userType" required>
        <option value="">Select role</option>
        <option value="agent">Agent</option>
        <option value="buyer">Buyer</option>
        <option value="renter">Renter</option>
    </select>

    <button type="submit">Register</button>
</form>

<?php include 'includes/footer.php'; ?>

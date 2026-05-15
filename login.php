<?php
// Loads the database class
require_once 'classes/RealEstateDatabase.php';

// Stores login error messages
$message = '';

// Checks if the login form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Creates a database object
    $db = new RealEstateDatabase();

    // Gets the username from the form and removes extra spaces
    $userName = trim($_POST['userName'] ?? '');

    // Gets the password from the form
    $password = $_POST['password'] ?? '';

    // Finds the user in the database by username
    $user = $db->getUserByUsername($userName);

    // Verifies the entered password against the hashed password in the database
    if ($user && password_verify($password, $user['passwordHash'])) {

        // Saves the logged-in user in the session
        $_SESSION['user'] = $user;

        // Sends the user to the dashboard after successful login
        header('Location: dashboard.php');
        exit;
    } else {
        // Shows an error if login fails
        $message = 'Invalid username or password.';
    }
}
?>

<?php include 'includes/header.php'; ?>

<h2>Login</h2>

<?php if ($message): ?>
    <p class="error"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Username</label>
    <input type="text" name="userName" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
</form>

<?php include 'includes/footer.php'; ?>

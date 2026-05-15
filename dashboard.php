<?php
// Loads the config file and authentication functions
require_once 'config/config.php';
require_once 'includes/auth.php';

// Makes sure the user is logged in before viewing the dashboard
requireLogin();

// Gets the logged-in user's information from the session
$user = $_SESSION['user'];
?>

<?php include 'includes/header.php'; ?>

<h2>Dashboard</h2>

<div class="card">
    <!-- Displays the logged-in user's name -->
    <p><strong>Welcome:</strong> <?= htmlspecialchars($user['userName']) ?></p>

    <!-- Displays the logged-in user's role -->
    <p><strong>Role:</strong> <?= htmlspecialchars($user['userType']) ?></p>
</div>

<?php if ($user['userType'] === 'agent'): ?>
    <div class="card">
        <h3>Agent Actions</h3>

        <!-- Shows Add Property link only for agents -->
        <a href="add_property.php">Add Property</a>
    </div>
<?php endif; ?>

<div class="card">
    <h3>Common Actions</h3>

    <!-- Link for all users to browse properties -->
    <a href="properties.php">Browse Properties</a>
</div>

<?php include 'includes/footer.php'; ?>

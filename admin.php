<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Restrict access to admins only
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header("Location: index.php");
    exit;
}
?>

<?php include 'includes/header.php'; ?>

<main class="admin-dashboard">
    <h1>Admin Dashboard</h1>

    <div class="admin-options">
        <a href="signup.php" class="admin-button">➕ New User</a>
        <a href="addemployee.php" class="admin-button">📇 New Rolodex Entry</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

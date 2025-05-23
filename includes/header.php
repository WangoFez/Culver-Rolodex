<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$firstName = $_SESSION['username'] ?? null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Rolodex</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>



<nav class="navbar">
    <div class="navbar-left">
        <?php if ($firstName): ?>
            <span>Hi, <?php echo htmlspecialchars($firstName); ?>!</span>
        <?php endif; ?>
    </div>

    <ul class="navbar-right">
        <li><a href="index.php">Home</a></li>
        <li><a href="admin.php">Admin</a></li>
        <?php if ($firstName): ?>
            <li><a href="logout.php">Log Out</a></li>
        <?php else: ?>
            <li><a href="login.php">Log In</a></li>
        <?php endif; ?>
    </ul>
</nav>

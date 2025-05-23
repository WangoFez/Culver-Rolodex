<?php
session_start();
require_once 'includes/db.php'; // DB connection

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        // Fetch user by username, include is_admin
        $stmt = $conn->prepare("SELECT id, password_hash, is_admin FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password_hash'])) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['is_admin'] = (int)$user['is_admin'];

                header('Location: index.php');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } else {
            $error = 'Invalid username or password.';
        }

        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="form-container">
    <h1>Log In</h1>

    <?php if ($error): ?>
        <div style="color: red; text-align: center; margin-top: 20px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" style="max-width: 400px; margin: auto;">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required autofocus style="width: 100%; padding: 8px; margin-bottom: 15px;"><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required style="width: 100%; padding: 8px; margin-bottom: 20px;"><br>

        <button type="submit" class="btn-submit">Log In</button>
    </form>
    <p style="text-align: center; margin-top: 20px;">
    Don't have an account? <a href="signup.php">Sign up here</a>.
</p>

</main>

<?php include 'includes/footer.php'; ?>



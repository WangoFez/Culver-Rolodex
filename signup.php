<?php
session_start();
require_once 'includes/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Check for duplicate username
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Username is already taken.';
        } else {
            // Hash password and insert user
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, password_hash, is_admin) VALUES (?, ?, ?)");
            $insert->bind_param('ssi', $username, $password_hash, $is_admin);

            if ($insert->execute()) {
                $success = 'Account created successfully. You can now <a href="login.php">log in</a>.';
            } else {
                $error = 'Something went wrong. Please try again.';
            }

            $insert->close();
        }

        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="form-container">
    <h1>Sign Up</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php elseif ($success): ?>
        <div style="color: green; text-align: center; margin-top: 20px;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form action="signup.php" method="POST" style="max-width: 400px; margin: auto;">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required style="width: 100%; padding: 8px; margin-bottom: 15px;"><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required style="width: 100%; padding: 8px; margin-bottom: 15px;"><br>

        <label for="confirm_password">Confirm Password:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required style="width: 100%; padding: 8px; margin-bottom: 15px;"><br>

        <label>
            <input type="checkbox" name="is_admin" value="1">
            Make this user an admin
        </label><br><br>

        <button type="submit" class="btn-submit">Sign Up</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>

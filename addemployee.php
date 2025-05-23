<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/db.php';

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $department = trim($_POST['department'] ?? '');

    if (!$first_name || !$last_name || !$email || !$phone_number || !$department) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $conn->prepare("INSERT INTO employees (first_name, last_name, email, phone_number, department) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $first_name, $last_name, $email, $phone_number, $department);

        if ($stmt->execute()) {
            $success = 'Employee added successfully.';
            $first_name = $last_name = $email = $phone_number = $department = '';
        } else {
            $error = 'Failed to add employee. Please try again.';
        }

        $stmt->close();
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="form-container">
    <h1>Add New Employee</h1>

    <?php if ($error): ?>
        <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form action="addemployee.php" method="POST" class="form">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number ?? ''); ?>" required>

        <label for="department">Department:</label>
        <input type="text" id="department" name="department" value="<?php echo htmlspecialchars($department ?? ''); ?>" required>

        <button type="submit" class="btn-submit">Add Employee</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>

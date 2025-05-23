<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Get search term from GET param (if any)
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    // Search query (no limit)
    $search_param = '%' . $search . '%';
    $stmt = $conn->prepare("SELECT * FROM employees WHERE 
        first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone_number LIKE ? OR department LIKE ? 
        ORDER BY last_name, first_name");
    $stmt->bind_param('sssss', $search_param, $search_param, $search_param, $search_param, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // No search, show only first 12 employees
    $sql = "SELECT * FROM employees ORDER BY last_name ASC LIMIT 12";
    $result = $conn->query($sql);
}
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Employee Rolodex</h1>

    <form method="GET" action="index.php" class="search-container" style="text-align:center; margin-bottom:20px;">
        <input 
            type="text" 
            name="search" 
            id="employeeSearch" 
            placeholder="Search employees..." 
            value="<?php echo htmlspecialchars($search); ?>" 
            style="padding: 8px; width: 300px; max-width: 90%;" 
        />
        <button type="submit" class="search-btn">Search</button>
        <?php if ($search !== ''): ?>
            <a href="index.php" style="margin-left: 15px; color: #007BFF; text-decoration: underline;">Clear Search</a>
        <?php endif; ?>
    </form>

    <div class="employee-list">
        <?php
        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $fullName = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                $jobTitle = htmlspecialchars($row['job_title']);
                $email = htmlspecialchars($row['email']);
                $phone = htmlspecialchars($row['phone_number']);
                $department = htmlspecialchars($row['department']);
        ?>
            <div class="employee-card">
                <h2><?php echo $fullName; ?></h2>
                <p><strong>Title:</strong> <?php echo $jobTitle; ?></p>
                <p><strong>Department:</strong> <?php echo $department; ?></p>
                <p><strong>Email:</strong> <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
                <p><strong>Phone:</strong> <?php echo $phone; ?></p>
            </div>
        <?php
            endwhile;
        else:
            echo '<p style="text-align:center;">No employees found.</p>';
        endif;

        $conn->close();
        ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<?php
// Connect to the database
require_once 'includes/db.php';
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Employee Rolodex</h1>

    <div class="search-container">
        <input type="text" id="employeeSearch" placeholder="Search employees..." />
    </div>

    <div class="employee-list">
        <?php
        // Query all employees
        $sql = "SELECT * FROM employees ORDER BY last_name ASC LIMIT 12";
        $result = $conn->query($sql);

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
            echo "<p>No employees found in the database.</p>";
        endif;

        $conn->close();
        ?>
    </div>
</main>

<script>
document.getElementById('employeeSearch').addEventListener('input', function () {
    const query = this.value.toLowerCase();
    const cards = document.querySelectorAll('.employee-card');

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(query) ? 'block' : 'none';
    });
});
</script>


<?php include 'includes/footer.php'; ?>

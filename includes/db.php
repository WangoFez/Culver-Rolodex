<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // XAMPP default is blank
$dbname = 'employee_rolodex';


$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php
$db_host = "localhost"; // Replace with your database host
$db_user = "root";      // Replace with your database username
$db_pass = "";          // Replace with your database password
$db_name = "cricket_scoreboard_db"; // Replace with your database name

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
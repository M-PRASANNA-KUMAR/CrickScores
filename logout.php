<?php
session_start();
session_destroy();
header('Location: login.php'); // Redirects to the login page
exit(); // It's good practice to call exit after header redirection
?>

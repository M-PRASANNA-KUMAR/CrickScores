<?php
$error = '';
include 'includes/dbconnection.php';
session_start(); // Start the session to manage user login sessions

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $player_id = $_POST['player_id'];
    $password = $_POST['password'];

    // Check for user credentials
    $sql = "SELECT user_id, password FROM users WHERE player_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $player_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            header('Location: pages/dashboard.php');  // Redirect to the user dashboard
            exit();  // Stop further execution after redirect
        } else {
            $error = "Incorrect password.";
        }
    } else {
        // Check for admin credentials
        $sql_admin = "SELECT admin_id, password FROM admin WHERE username = ?";
        $stmt_admin = $conn->prepare($sql_admin);
        $stmt_admin->bind_param("s", $player_id); // Using player_id field for admin username for simplicity in login page
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        if ($result_admin->num_rows == 1) {
            $admin = $result_admin->fetch_assoc();
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                header('Location: admin/admin_dashboard.php');  // Redirect to the admin dashboard
                exit();  // Stop further execution after redirect
            } else {
                $error = "Incorrect admin password.";
            }
        } else {
            $error = "Invalid Player ID or Admin Username.";
        }
    }

    $stmt->close();
}
?>

<link rel="stylesheet" href="css/style.css">
<header class="header" id="header">
    <nav class="nav">
        <a href="index.php" class="nav__logo"><img src="images/Whitelogo.png" alt="cricket logo"></a>
        <div class="nav__menu" id="nav-menu">
            <ul class="nav__list">
                <div class="nav__link">
                    <a href="login.php" class="button">Log In</a>
                </div>
                <div class="nav__link">
                    <a href="register.php" class="button">Register</a>
                </div>
            </ul>
        </div>
    </nav>
</header>

<div class="main-wrapper">
    <div class="lcontainer">
        <center><h2>Login</h2></center>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="player_id">Player ID / Admin Username:</label>
            <input type="text" id="player_id" name="player_id" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <center><button type="submit">Login</button></center>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>

<?php
include_once '../includes/header.php';

if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Fetch admin details (assuming you have only one admin user for simplicity)
$sql_admin = "SELECT * FROM admin WHERE admin_id = ?"; // Assuming admin_id is always 1 for the main admin
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("i", $_SESSION['admin_id']);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();
$admin_details = $result_admin->fetch_assoc();
$stmt_admin->close();

$error_password = '';
$success_password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!password_verify($old_password, $admin_details['password'])) {
        $error_password = "Incorrect old password.";
    } elseif ($new_password != $confirm_password) {
        $error_password = "New password and confirm password do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_update_password = "UPDATE admin SET password = ? WHERE admin_id = ?";
        $stmt_update_password = $conn->prepare($sql_update_password);
        $stmt_update_password->bind_param("si", $hashed_password, $_SESSION['admin_id']);
        if ($stmt_update_password->execute()) {
            $success_password = "Password changed successfully.";
        } else {
            $error_password = "Failed to change password. Please try again.";
        }
        $stmt_update_password->close();
    }
}

?>
<div class="main-wrapper">
<div class="container">
    <h2>Admin Profile</h2>

    <?php if ($admin_details): ?>
        <div class="profile-details">
            <p><strong>Username:</strong> <?php echo htmlspecialchars($admin_details['username']); ?></p>
        </div>

        <div class="change-password-form">
            <h3>Change Password</h3>
            <?php if ($error_password): ?>
                <div class="error"><?php echo $error_password; ?></div>
            <?php endif; ?>
            <?php if ($success_password): ?>
                <div class="success"><?php echo $success_password; ?></div>
            <?php endif; ?>
            <form action="admin_profile.php" method="post">
                <label for="old_password">Old Password:</label>
                <input type="password" id="old_password" name="old_password" required>

                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>

                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit" name="change_password">Change Password</button>
            </form>
        </div>

    <?php else: ?>
        <p class="error">Error fetching admin profile details.</p>
    <?php endif; ?>
</div>
    </div>
<?php include_once '../includes/footer.php'; ?>
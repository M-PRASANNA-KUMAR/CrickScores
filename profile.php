<?php
include_once '../includes/header.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

$user_id = $_SESSION['user_id'];
$player_id = 'P' . str_pad($user_id, 3, '0', STR_PAD_LEFT);

// Fetch user details
$sql_user = "SELECT * FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user_details = $result_user->fetch_assoc();
$stmt_user->close();

$error_password = '';
$success_password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!password_verify($old_password, $user_details['password'])) {
        $error_password = "Incorrect old password.";
    } elseif ($new_password != $confirm_password) {
        $error_password = "New password and confirm password do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_update_password = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt_update_password = $conn->prepare($sql_update_password);
        $stmt_update_password->bind_param("si", $hashed_password, $user_id);
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
    <h2>My Profile</h2>

    <?php if ($user_details): ?>
        <div class="profile-details">
            <p><strong>Player ID:</strong> <?php echo htmlspecialchars($user_details['player_id']); ?></p>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user_details['name']); ?></p>
            <p><strong>Age:</strong> <?php echo htmlspecialchars($user_details['age']); ?></p>
            <p><strong>Height:</strong> <?php echo htmlspecialchars($user_details['height']); ?></p>
            <p><strong>Weight:</strong> <?php echo htmlspecialchars($user_details['weight']); ?></p>
            <?php if ($user_details['profile_pic']): ?>
                <p><strong>Profile Picture:</strong></p>
                <img src="<?php echo htmlspecialchars($user_details['profile_pic']); ?>" alt="Profile Picture" class="profile-pic-preview">
            <?php endif; ?>
        </div>

        <div class="change-password-form">
            <h3>Change Password</h3>
            <?php if ($error_password): ?>
                <div class="error"><?php echo $error_password; ?></div>
            <?php endif; ?>
            <?php if ($success_password): ?>
                <div class="success"><?php echo $success_password; ?></div>
            <?php endif; ?>
            <form action="profile.php" method="post">
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
        <p class="error">Error fetching profile details.</p>
    <?php endif; ?>
</div>
</div>
<?php include_once '../includes/footer.php'; ?>
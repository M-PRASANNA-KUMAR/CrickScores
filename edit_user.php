<?php
include_once '../includes/header.php';

if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Database connection check
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Edit User - Fetch user details
if (isset($_GET['edit_id'])) {
    $edit_user_id = $_GET['edit_id'];

    $sql_edit = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql_edit);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $edit_user_id);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if (!$result) {
        die("Get result failed: " . $stmt->error);
    }

    $user = $result->fetch_assoc();

    if (!$user) {
        die("User not found.");
    }

    $stmt->close();
}

// Update User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $profile_pic = $user['profile_pic']; // Default to existing image

    // Handle file upload
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "../uploads/";
        $file_name = basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Validate file type
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                $profile_pic = "uploads/" . $file_name;
            } else {
                die("Error uploading image.");
            }
        } else {
            die("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
        }
    }

    $sql_update = "UPDATE users SET name = ?, age = ?, height = ?, weight = ?, profile_pic = ? WHERE user_id = ?";
    $stmt_update = $conn->prepare($sql_update);

    if (!$stmt_update) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt_update->bind_param("siiisi", $name, $age, $height, $weight, $profile_pic, $user_id);

    if (!$stmt_update->execute()) {
        die("Execute failed: " . $stmt_update->error);
    }

    $stmt_update->close();
    redirect('manage_users.php'); // Redirect after update
}
?>
<div class="main-wrapper">
<div class="container">
    <h2>Edit User</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        <label>Age:</label>
        <input type="number" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" required>
        <label>Height:</label>
        <input type="number" step="0.1" name="height" value="<?php echo htmlspecialchars($user['height']); ?>" required>
        <label>Weight:</label>
        <input type="number" step="0.1" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>" required>
        <label>Profile Picture:</label>
        <input type="file" name="profile_pic">
        <?php if ($user['profile_pic']): ?>
            <img src="../<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Pic" style="max-width: 100px; max-height: 100px;">
        <?php endif; ?>
        <button type="submit" name="update_user">Update</button>
    </form>
</div>
        </div>
<?php include_once '../includes/footer.php'; ?>
<?php
$error = '';
$success_msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $password = $_POST['password'];

    // Profile Picture Upload
    $profile_pic_path = '';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowed_exts = array("jpg", "jpeg", "png", "gif");
        $temp = explode(".", $_FILES["profile_pic"]["name"]);
        $extension = strtolower(end($temp));

        if (in_array($extension, $allowed_exts)) {
            $profile_pic_name = uniqid() . "_" . $_FILES['profile_pic']['name'];
            $profile_pic_path = 'images/profile_pics/' . $profile_pic_name;
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic_path);
        } else {
            $error = "Invalid file type for profile picture. Allowed types: jpg, jpeg, png, gif.";
        }
    }

    if (empty($error)) {
        $player_id = generatePlayerId($conn);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (player_id, name, age, height, weight, profile_pic, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissss", $player_id, $name, $age, $height, $weight, $profile_pic_path, $hashed_password);

        if ($stmt->execute()) {
            $success_msg = "Registration successful! Your Player ID is: <strong>" . htmlspecialchars($player_id) . "</strong>. You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Registration failed. Please try again.";
        }
        $stmt->close();
    }
}
?>

<header class="header" id="header">
        <nav class="nav ">
            <a href="index.php" class="nav__logo"><img src="images/Whitelogo.png" alt="cricket logo"></a>
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <div class="nav__link">
                        <a href="login.php" class="button ">Log In</a>
                    </div>
                    <div class="nav__link">
                        <a href="register.php" class="button" >Register</a>
                    </div>
                </ul>
            </div>
        </nav>
    </header>
    <div class="main-wrapper">
<div class="lcontainer">
  <center>  <h2>Register</h2></center>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success_msg): ?>
        <div class="success"><?php echo htmlspecialchars($success_msg); ?></div>
    <?php endif; ?>
    <?php if (!$success_msg): ?>
    <form action="register.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age">

        <label for="height">Height:</label>
        <input type="text" id="height" name="height">

        <label for="weight">Weight:</label>
        <input type="text" id="weight" name="weight">

        <label for="profile_pic">Profile Picture:</label>
        <input type="file" id="profile_pic" name="profile_pic" accept="image/*">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

     <center>   <button type="submit" >Register</button>
    </form>
    <?php endif; ?>
</div>
    </div>
<?php include_once 'includes/footer.php'; ?>
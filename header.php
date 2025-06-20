<?php
session_start(); 
require_once 'C:\xampp\htdocs\cricket_scoreboard\includes\dbconnection.php';
require_once 'C:\xampp\htdocs\cricket_scoreboard\includes\functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cricket Scoreboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header class="header" id="header">
        <nav class="nav ">
            <a href="dashboard.php" class="nav__logo"><img src="../images/Whitelogo.png" alt="cricket logo"></a>
             <ul>
                    <?php if (isLoggedIn()): ?>
                        <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <div class="nav__link">
                        <li><a href="dashboard.php"class="button ">Dashboard</a></li>
                        </div>
                    <div class="nav__link">
                        <li><a href="my_matches.php"class="button ">My Matches</a></li>
                        </div>
                    <div class="nav__link">
                        <li><a href="profile.php"class="button ">Profile</a></li>
                        </div>
                    <div class="nav__link">
                        <li><a href="../logout.php"class="button ">Logout</a></li>
                        </div>
                </ul>
            </div>
                    <?php elseif (isAdminLoggedIn()): ?>
                        <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <div class="nav__link">
                        <li><a href="admin_dashboard.php"
                        <a href="login.php" class="button ">Admin Dashboard</a></li>
                        </div>
                    <div class="nav__link">
                        <li><a href="manage_users.php"
                        <a href="login.php" class="button ">Manage Users</a></li>
                        </div>
                    <div class="nav__link">
                        <li><a href="admin_profile.php"
                        <a href="login.php" class="button ">Admin Profile</a></li>
                    </div>
                    <div class="nav__link">
                        <li><a href="../logout.php"
                        <a href="login.php" class="button ">Logout</a></li>
                    </div>
                    </ul>
                    </div>
                    <?php else: ?>
                 <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <div class="nav__link">
                        <a href="login.php" class="button ">Log In</a>
                    </div>
                    <div class="nav__link">
                        <a href="register.php" class="button" >Register</a>
                    </div>
                </ul>
            </div> <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>
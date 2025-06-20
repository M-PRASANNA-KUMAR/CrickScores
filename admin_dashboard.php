<?php
include_once '../includes/header.php';

if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Get total users count
$sql_users_count = "SELECT COUNT(*) AS total_users FROM users";
$result_users_count = $conn->query($sql_users_count);
$total_users = $result_users_count->fetch_assoc()['total_users'] ?? 0;

// Get total matches played count
$sql_matches_count = "SELECT COUNT(*) AS total_matches FROM matches";
$result_matches_count = $conn->query($sql_matches_count);
$total_matches = $result_matches_count->fetch_assoc()['total_matches'] ?? 0;

?>
<div class="main-wrapper">
<div class="container">
    <h2>Admin Dashboard</h2>

    <div class="dashboard-summary">
        <div class="summary-box">
            <h3>Total Users</h3>
            <p><?php echo $total_users; ?></p>
        </div>

        <div class="summary-box">
            <h3>Total Matches Played</h3>
            <p><?php echo $total_matches; ?></p>
        </div>

        <!-- Add more summary boxes as needed here -->
    </div>

    <style>
        .dashboard-summary {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .summary-box {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            flex: 1; /* Distribute space evenly */
        }

        .summary-box h3 {
            margin-top: 0;
            color: #555;
        }

        .summary-box p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
    </style>
</div>
    </div>
<?php include_once '../includes/footer.php'; ?>
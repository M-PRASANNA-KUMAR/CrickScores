<?php
include_once '../includes/header.php';

if (!isAdminLoggedIn()) {
    redirect('../login.php');
}

// Delete User
if (isset($_GET['delete_id'])) {
    $delete_user_id = $_GET['delete_id'];
    $sql_delete = "DELETE FROM users WHERE user_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $delete_user_id);
    $stmt_delete->execute();
    $stmt_delete->close();
    redirect('manage_users.php'); // Refresh page after delete
}

// Fetch all users
$sql_users = "SELECT * FROM users ORDER BY created_at DESC";
$result_users = $conn->query($sql_users);

?>
<div class="main-wrapper">
<div class="container">
    <h2>Manage Users</h2>

    <?php if ($result_users->num_rows > 0): ?>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Player ID</th>
                    <th>Name</th>
                    <th>Age</th>
                    <th>Height</th>
                    <th>Weight</th>
                    <th>Profile Pic</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $result_users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['player_id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['age']); ?></td>
                        <td><?php echo htmlspecialchars($user['height']); ?></td>
                        <td><?php echo htmlspecialchars($user['weight']); ?></td>
                        <td>
                            <?php if ($user['profile_pic']): ?>
                                <img src="../<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile Pic" style="max-width: 50px; max-height: 50px;">
                            <?php else: ?>
                                No Pic
                            <?php endif; ?>
                        </td>
                        <td><?php echo date("M j, Y", strtotime($user['created_at'])); ?></td>
                        <td>
                            <a href="edit_user.php?edit_id=<?php echo $user['user_id']; ?>">Edit</a> |
                            <a href="manage_users.php?delete_id=<?php echo $user['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users registered yet.</p>
    <?php endif; ?>

    <style>
        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            color :rgb(0, 0, 0)
        }

        .user-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .user-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</div>
    </div>
<?php include_once '../includes/footer.php'; ?>
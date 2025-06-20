<?php
include_once '../includes/header.php';

if (!isLoggedIn()) {
    redirect('../login.php');
    exit(); // Ensure the script stops after redirecting
}

// Fetch matches played by the user
$user_id = $_SESSION['user_id'];
$player_id_prefix = 'P' . str_pad($user_id, 3, '0', STR_PAD_LEFT);

$sql_matches = "SELECT * FROM matches WHERE team1_players LIKE ? OR team2_players LIKE ? ORDER BY match_date DESC";
$stmt_matches = $conn->prepare($sql_matches);
$search_player_id = '%' . $player_id_prefix . '%';
$stmt_matches->bind_param("ss", $search_player_id, $search_player_id);
$stmt_matches->execute();
$result_matches = $stmt_matches->get_result();

?>
<div class="main-wrapper">
<div class="container">
    <h2>My Matches</h2>

    <?php if ($result_matches->num_rows > 0): ?>
        <div class="matches-list">
            <?php while ($match = $result_matches->fetch_assoc()): ?>
                <div class="match-item">
                    <h3><?php echo htmlspecialchars($match['team1_name']); ?> vs <?php echo htmlspecialchars($match['team2_name']); ?></h3>
                    <p>Date: <?php echo date("M j, Y", strtotime($match['match_date'])); ?></p>
                    <p><a href="match_summary.php?match_id=<?php echo htmlspecialchars($match['match_id']); ?>">View Match Summary</a></p>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No matches played yet.</p>
    <?php endif; ?>
</div>
</div>
<?php include_once '../includes/footer.php'; ?>
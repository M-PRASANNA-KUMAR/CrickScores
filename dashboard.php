<?php
include_once '../includes/header.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}
$user_id = $_SESSION['user_id'];
$player_id_prefix = 'P' . str_pad($user_id, 3, '0', STR_PAD_LEFT);
$player_name = getPlayerName($conn, $player_id_prefix);

?>
<div class="main-wrapper">
<div class="container">
<div class="start-match-container">
        <h2>Welcome to your Dashboard, <?php echo htmlspecialchars($player_name); ?>!<br>Start a New Match</h2>
        <p>Ready to begin a cricket match? Click below to set up your game!</p>
        <a href="start_match.php" class="button">Start Match</a>
    </div>
</div>
</div>
<?php include_once '../includes/footer.php'; ?>
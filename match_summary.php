<?php
include_once '../includes/header.php';

if (!isLoggedIn()) {
    redirect('../login.php');
    exit(); // Ensure the script stops after redirecting
}

// Check if match_id is set in the GET parameters
if (!isset($_GET['match_id'])) {
    redirect('my_matches.php'); // Redirect if match_id is not provided
    exit();
}

$match_id = $_GET['match_id']; // Get match_id from GET parameter
$match_details = getMatchDetails($conn, $match_id);

if (!$match_details) {
    redirect('my_matches.php'); // Match not found
    exit();
}

$team1_players_ids = explode(',', $match_details['team1_players']);
$team2_players_ids = explode(',', $match_details['team2_players']);

// Function to get player score and wickets (no changes needed here)
function getPlayerStats($conn, $match_id, $playerId, $innings_table) {
    $sql = "SELECT SUM(score) AS total_score, SUM(wicket) AS total_wickets FROM $innings_table WHERE match_id = ? AND batting_player_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $match_id, $playerId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats = $result->fetch_assoc();
    return $stats ? $stats : ['total_score' => 0, 'total_wickets' => 0];
}

// Get team scores and wickets (no changes needed here)
$team1_total_score = 0;
$team1_total_wickets = 0;
$team2_total_score = 0;
$team2_total_wickets = 0;

$sql_team1_summary = "SELECT SUM(score) AS total_score, SUM(wicket) AS total_wickets FROM innings1_scores WHERE match_id = ?";
$stmt_team1_summary = $conn->prepare($sql_team1_summary);
$stmt_team1_summary->bind_param("i", $match_id);
$stmt_team1_summary->execute();
$result_team1_summary = $stmt_team1_summary->get_result();
if ($result_team1_summary->num_rows > 0) {
    $row_team1_summary = $result_team1_summary->fetch_assoc();
    $team1_total_score = $row_team1_summary['total_score'] ?? 0;
    $team1_total_wickets = $row_team1_summary['total_wickets'] ?? 0;
}
$stmt_team1_summary->close();

$sql_team2_summary = "SELECT SUM(score) AS total_score, SUM(wicket) AS total_wickets FROM innings2_scores WHERE match_id = ?";
$stmt_team2_summary = $conn->prepare($sql_team2_summary);
$stmt_team2_summary->bind_param("i", $match_id);
$stmt_team2_summary->execute();
$result_team2_summary = $stmt_team2_summary->get_result();
if ($result_team2_summary->num_rows > 0) {
    $row_team2_summary = $result_team2_summary->fetch_assoc();
    $team2_total_score = $row_team2_summary['total_score'] ?? 0;
    $team2_total_wickets = $row_team2_summary['total_wickets'] ?? 0;
}
$stmt_team2_summary->close();


// Determine winning team (no changes needed here)
$winning_team = '';
if ($team1_total_score > $team2_total_score) {
    $winning_team = $match_details['team1_name'];
} else if ($team2_total_score > $team1_total_score) {
    $winning_team = $match_details['team2_name'];
} else {
    $winning_team = "Match Tied"; // Or handle draw logic if needed
}

?>
<div class="main-wrapper">
<div class="container match-summary-container">
    <h2>Match Summary</h2>
    <h3><?php echo htmlspecialchars($match_details['team1_name']); ?> vs <?php echo htmlspecialchars($match_details['team2_name']); ?></h3>

    <?php if ($winning_team != "Match Tied"): ?>
        <p><strong>Winning Team:</strong> <?php echo htmlspecialchars($winning_team); ?></p>
    <?php else: ?>
        <p><strong>Match Result:</strong> Tied</p>
    <?php endif; ?>

    <div class="team-summary">
        <h4><?php echo htmlspecialchars($match_details['team1_name']); ?></h4>
        <p><strong>Total Score:</strong> <?php echo htmlspecialchars($team1_total_score); ?> runs</p>
        <p><strong>Total Wickets:</strong> <?php echo htmlspecialchars($team1_total_wickets); ?></p>
        <h5>Players' Scores and Wickets</h5>
        <ul>
            <?php foreach ($team1_players_ids as $player_id): ?>
                <li>
                    <?php $player_stats = getPlayerStats($conn, $match_id, $player_id, 'innings1_scores'); ?>
                    <?php echo htmlspecialchars(getPlayerName($conn, $player_id)); ?> (<?php echo htmlspecialchars($player_id); ?>) - Runs: <?php echo htmlspecialchars($player_stats['total_score']); ?>, Wickets: <?php echo htmlspecialchars($player_stats['total_wickets']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="team-summary">
        <h4><?php echo htmlspecialchars($match_details['team2_name']); ?></h4>
        <p><strong>Total Score:</strong> <?php echo htmlspecialchars($team2_total_score); ?> runs</p>
        <p><strong>Total Wickets:</strong> <?php echo htmlspecialchars($team2_total_wickets); ?></p>
        <h5>Players' Scores and Wickets</h5>
        <ul>
            <?php foreach ($team2_players_ids as $player_id): ?>
                <li>
                    <?php $player_stats = getPlayerStats($conn, $match_id, $player_id, 'innings2_scores'); ?>
                    <?php echo htmlspecialchars(getPlayerName($conn, $player_id)); ?> (<?php echo htmlspecialchars($player_id); ?>) - Runs: <?php echo htmlspecialchars($player_stats['total_score']); ?>, Wickets: <?php echo htmlspecialchars($player_stats['total_wickets']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Add more summary details like top scorer, top wicket-taker, over-wise scores, etc. as needed here -->

</div>
</div>
<?php include_once '../includes/footer.php'; ?>
<?php
include_once '../includes/header.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!isset($_SESSION['current_match_id']) || !isset($_SESSION['innings'])) {
    redirect('start_match.php'); // Go back if match not properly started
}

$match_id = $_SESSION['current_match_id'];
$innings_number = $_SESSION['innings']; // 1 or 2 - should be 1 here for score_team1.php
$match_details = getMatchDetails($conn, $match_id);

if (!$match_details) {
    redirect('dashboard.php'); // Match not found
}

$batting_team_name = ($innings_number == 1) ? $match_details['batting_team'] : ($match_details['batting_team'] == $match_details['team1_name'] ? $match_details['team2_name'] : $match_details['team1_name']);
$bowling_team_name = ($innings_number == 1) ? $match_details['bowling_team'] : ($match_details['bowling_team'] == $match_details['team1_name'] ? $match_details['team2_name'] : $match_details['team1_name']);
$batting_team_players_ids = ($batting_team_name == $match_details['team1_name']) ? explode(',', $match_details['team1_players']) : explode(',', $match_details['team2_players']);
$bowling_team_players_ids = ($bowling_team_name == $match_details['team1_name']) ? explode(',', $match_details['team1_players']) : explode(',', $match_details['team2_players']);

$innings_table = 'innings1_scores'; // Always innings1_scores for score_team1.php

$current_over = 0;
$current_ball_in_over = 0;
$total_score = 0;
$wickets_fallen = 0;
$remaining_balls = $match_details['overs'] * 6;
$out_batters = [];

// Get current score and wickets from database
$sql_score = "SELECT SUM(score) AS total_score, SUM(wicket) AS total_wickets, COUNT(*) AS balls_bowled FROM $innings_table WHERE match_id = ?";
$stmt_score = $conn->prepare($sql_score);
$stmt_score->bind_param("i", $match_id);
$stmt_score->execute();
$result_score = $stmt_score->get_result();
if ($result_score->num_rows > 0) {
    $row_score = $result_score->fetch_assoc();
    $total_score = $row_score['total_score'] ?? 0;
    $wickets_fallen = $row_score['total_wickets'] ?? 0;
    $balls_bowled = $row_score['balls_bowled'] ?? 0;
    $remaining_balls = max(0, ($match_details['overs'] * 6) - $balls_bowled);
    $current_over = floor($balls_bowled / 6);
    $current_ball_in_over = $balls_bowled % 6;
}
$stmt_score->close();

// Fetch out batters for dropdown management
$sql_out_batters = "SELECT batter_out_player_id FROM $innings_table WHERE match_id = ? AND wicket = 1 AND batter_out_player_id IS NOT NULL";
$stmt_out_batters = $conn->prepare($sql_out_batters);
$stmt_out_batters->bind_param("i", $match_id);
$stmt_out_batters->execute();
$result_out_batters = $stmt_out_batters->get_result();
while ($row_out_batter = $result_out_batters->fetch_assoc()) {
    $out_batters[] = $row_out_batter['batter_out_player_id'];
}
$stmt_out_batters->close();

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $batting_player_id = $_POST['batting_player_id'];
    $bowling_player_id = $_POST['bowling_player_id'];
    $score = $_POST['score'];
    $wicket = isset($_POST['wicket']) ? 1 : 0;
    $batter_out_player_id = $wicket ? $batting_player_id : null;

    $current_ball_in_over++;
    if ($current_ball_in_over > 6) {
        $current_over++;
        $current_ball_in_over = 1;
    }

    // **Create temporary variables for expressions:**
    $ball_number_for_insert = $balls_bowled + 1;
    $over_number_for_insert = $current_over + 1;

    $sql_insert_score = "INSERT INTO $innings_table (match_id, ball_number, over_number, batting_player_id, bowling_player_id, score, wicket, batter_out_player_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_score = $conn->prepare($sql_insert_score);
    // **Pass temporary variables to bind_param:**
    $stmt_insert_score->bind_param("iiiisiis", $match_id, $ball_number_for_insert, $over_number_for_insert, $batting_player_id, $bowling_player_id, $score, $wicket, $batter_out_player_id);

    if ($stmt_insert_score->execute()) {
        // Update score and wickets (already done in initial query for display)
        $total_score += $score;
        if ($wicket) {
            $wickets_fallen++;
            $out_batters[] = $batting_player_id; // Add to out batters for display
        }
        $remaining_balls--;

        // Check for innings end conditions
        if ($remaining_balls <= 0 || $wickets_fallen >= $match_details['wickets_allowed']) {
            $_SESSION['innings'] = 2; // Move to innings 2
            redirect('score_team2.php');
        } else {
            redirect('score_team1.php'); // Refresh current scoring page
        }
    } else {
        $error = "Failed to record score. Please try again.";
    }
    $stmt_insert_score->close();
}

?>
<div class="main-wrapper">
<div class="container score-container">
    <h2>Innings <?php echo htmlspecialchars($innings_number); ?> - Scoring for <?php echo htmlspecialchars($batting_team_name); ?></h2>

    <div class="score-header">
        <div class="current-score">Score: <?php echo htmlspecialchars($total_score); ?> - <?php echo htmlspecialchars($wickets_fallen); ?></div>
        <div class="remaining-balls">Remaining Balls: <?php echo htmlspecialchars($remaining_balls); ?> (Overs: <?php echo htmlspecialchars(floor($remaining_balls / 6)); ?>.<?php echo htmlspecialchars($remaining_balls % 6); ?>)</div>
    </div>

    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form class="score-form" action="score_team1.php" method="post">
        <label for="batting_player_id">Batter:</label>
        <select id="batting_player_id" name="batting_player_id" required>
            <option value="">Select Batter</option>
            <?php
            foreach ($batting_team_players_ids as $player_id):
                if (!in_array($player_id, $out_batters)): // Don't show out batters
                    $playerName = getPlayerName($conn, $player_id);
                    echo "<option value=\"" . htmlspecialchars($player_id) . "\">" . htmlspecialchars($playerName) . " (" . htmlspecialchars($player_id) . ")</option>";
                endif;
            endforeach;
            ?>
        </select>

        <label for="bowling_player_id">Bowler:</label>
        <select id="bowling_player_id" name="bowling_player_id" required>
            <option value="">Select Bowler</option>
            <?php
            foreach ($bowling_team_players_ids as $player_id):
                $playerName = getPlayerName($conn, $player_id);
                echo "<option value=\"" . htmlspecialchars($player_id) . "\">" . htmlspecialchars($playerName) . " (" . htmlspecialchars($player_id) . ")</option>";
            endforeach;
            ?>
        </select>

        <label for="score">Runs:</label>
        <input type="number" id="score" name="score" value="0" min="0">

        <label for="wicket">Wicket?</label>
        <input type="checkbox" id="wicket" name="wicket" value="1">

        <button type="submit">Submit Ball</button>
    </form>

    <p><a href="match_summary.php?match_id=<?php echo htmlspecialchars($match_id); ?>">View Match Summary (during match - for testing)</a></p>

</div>
</div>
<?php include_once '../includes/footer.php'; ?>
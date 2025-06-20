<?php
include_once '../includes/header.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

$error = '';
$team1_players_data = [];
$team2_players_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team1_name = $_POST['team1_name'];
    $team2_name = $_POST['team2_name'];
    $team1_player_count = $_POST['team1_player_count'];
    $team2_player_count = $_POST['team2_player_count'];

    $team1_players = [];
    for ($i = 1; $i <= $team1_player_count; $i++) {
        $player_id = $_POST['team1_player_id_' . $i];
        if (!empty($player_id)) {
            $team1_players[] = $player_id;
            $team1_players_data[$player_id] = getPlayerName($conn, $player_id); // Fetch player name
        }
    }

    $team2_players = [];
    for ($i = 1; $i <= $team2_player_count; $i++) {
        $player_id = $_POST['team2_player_id_' . $i];
        if (!empty($player_id)) {
            $team2_players[] = $player_id;
            $team2_players_data[$player_id] = getPlayerName($conn, $player_id); // Fetch player name
        }
    }

    if (empty($team1_name) || empty($team2_name) || empty($team1_players) || empty($team2_players)) {
        $error = "Please fill in all team names and player IDs.";
    } else {
        // Store team and player data in session for now, move to database in match_details.php
        $_SESSION['match_setup'] = [
            'team1_name' => $team1_name,
            'team2_name' => $team2_name,
            'team1_players' => $team1_players,
            'team2_players' => $team2_players,
            'team1_players_data' => $team1_players_data,
            'team2_players_data' => $team2_players_data
        ];
        redirect('match_details.php');
    }
}
?>
<div class="main-wrapper">
<div class="container">
    <h2>Start Match - Team Setup</h2>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form action="start_match.php" method="post">
        <div class="team-setup">
            <h3>Team 1</h3>
            <label for="team1_name">Team Name:</label>
            <input type="text" id="team1_name" name="team1_name" required>

            <label for="team1_player_count">Number of Players:</label>
            <input type="number" id="team1_player_count" name="team1_player_count" min="1" value="<?php echo isset($_POST['team1_player_count']) ? htmlspecialchars($_POST['team1_player_count']) : 11; ?>" required>

            <div id="team1_player_fields">
                <!-- Player ID fields for Team 1 will be generated here by JavaScript -->
            </div>
        </div>

        <div class="team-setup">
            <h3>Team 2</h3>
            <label for="team2_name">Team Name:</label>
            <input type="text" id="team2_name" name="team2_name" required>

            <label for="team2_player_count">Number of Players:</label>
            <input type="number" id="team2_player_count" name="team2_player_count" min="1" value="<?php echo isset($_POST['team2_player_count']) ? htmlspecialchars($_POST['team2_player_count']) : 11; ?>" required>

            <div id="team2_player_fields">
                <!-- Player ID fields for Team 2 will be generated here by JavaScript -->
            </div>
        </div>

        <button type="submit">Continue to Match Details</button>
    </form>
</div>
</div>
<script src="../js/script.js"></script> <!-- Include JavaScript file -->
<?php include_once '../includes/footer.php'; ?>
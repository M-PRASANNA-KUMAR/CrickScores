<?php
include_once '../includes/header.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

if (!isset($_SESSION['match_setup'])) {
    redirect('start_match.php'); // Go back if match setup not done
}

$match_setup = $_SESSION['match_setup'];
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $overs = $_POST['overs'];
    $wickets_allowed = $_POST['wickets_allowed'];
    $toss_winner_team = $_POST['toss_winner_team'];
    $batting_team = $_POST['batting_team'];
    $bowling_team = ($batting_team == $match_setup['team1_name']) ? $match_setup['team2_name'] : $match_setup['team1_name']; // Automatically set bowling team

    if (empty($overs) || empty($wickets_allowed) || empty($toss_winner_team) || empty($batting_team)) {
        $error = "Please fill in all match details.";
    } else {
        $team1_players_str = implode(",", $match_setup['team1_players']);
        $team2_players_str = implode(",", $match_setup['team2_players']);

        $sql = "INSERT INTO matches (team1_name, team2_name, team1_players, team2_players, overs, wickets_allowed, toss_winner_team, batting_team, bowling_team) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiisss", $match_setup['team1_name'], $match_setup['team2_name'], $team1_players_str, $team2_players_str, $overs, $wickets_allowed, $toss_winner_team, $batting_team, $bowling_team);

        if ($stmt->execute()) {
            $match_id = $conn->insert_id;
            $_SESSION['current_match_id'] = $match_id;
            $_SESSION['innings'] = 1; // Start with innings 1
            redirect('score_team1.php');
        } else {
            $error = "Failed to start match. Please try again.";
        }
        $stmt->close();
    }
}
?>
<div class="main-wrapper">
<div class="container">
    <h2>Match Details</h2>
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <div class="match-details-container">
        <h3>Team 1: <?php echo htmlspecialchars($match_setup['team1_name']); ?></h3>
        <ul>
            <?php foreach ($match_setup['team1_players_data'] as $playerId => $playerName): ?>
                <li><?php echo htmlspecialchars($playerName) . " (" . htmlspecialchars($playerId) . ")"; ?></li>
            <?php endforeach; ?>
        </ul>

        <h3>Team 2: <?php echo htmlspecialchars($match_setup['team2_name']); ?></h3>
        <ul>
            <?php foreach ($match_setup['team2_players_data'] as $playerId => $playerName): ?>
                <li><?php echo htmlspecialchars($playerName) . " (" . htmlspecialchars($playerId) . ")"; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>


    <form action="match_details.php" method="post">
        <label for="overs">Total Overs:</label>
        <input type="number" id="overs" name="overs" min="1" required value="20">

        <label for="wickets_allowed">Wickets Allowed (per innings):</label>
        <input type="number" id="wickets_allowed" name="wickets_allowed" min="1" max="10" value="10">

        <label for="toss_winner_team">Toss Winning Team:</label>
        <select id="toss_winner_team" name="toss_winner_team" required>
            <option value="<?php echo htmlspecialchars($match_setup['team1_name']); ?>"><?php echo htmlspecialchars($match_setup['team1_name']); ?></option>
            <option value="<?php echo htmlspecialchars($match_setup['team2_name']); ?>"><?php echo htmlspecialchars($match_setup['team2_name']); ?></option>
        </select>

        <label for="batting_team">Batting Team (First):</label>
        <select id="batting_team" name="batting_team" required>
            <option value="<?php echo htmlspecialchars($match_setup['team1_name']); ?>"><?php echo htmlspecialchars($match_setup['team1_name']); ?></option>
            <option value="<?php echo htmlspecialchars($match_setup['team2_name']); ?>"><?php echo htmlspecialchars($match_setup['team2_name']); ?></option>
        </select>

        <button type="submit">Start Scoring - Innings 1</button>
    </form>
</div>
</div>
<?php include_once '../includes/footer.php'; ?>
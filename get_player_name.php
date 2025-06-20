<?php
include_once 'dbconnection.php';
include_once 'functions.php';

$playerId = $_GET['player_id'] ?? ''; // Get player_id from GET request

if (!empty($playerId)) {
    $playerName = getPlayerName($conn, $playerId);
    if ($playerName) {
        echo htmlspecialchars($playerName); // Output player name if found
    } else {
        echo "Player not found"; // Output error message if not found
    }
} else {
    echo ""; // Output empty string if player_id is empty
}
?>
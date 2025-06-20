<?php
function generatePlayerId($conn) {
    $prefix = 'P';
    $number = 1;
    $sql = "SELECT MAX(SUBSTR(player_id, 2)) AS max_id FROM users WHERE player_id LIKE '{$prefix}%'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['max_id']) {
            $number = intval($row['max_id']) + 1;
        }
    }
    return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT); // P001, P002, etc.
}

function getPlayerName($conn, $playerId) {
    $sql = "SELECT name FROM users WHERE player_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $playerId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['name'];
    } else {
        return null; // Player not found
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function getMatchDetails($conn, $matchId) {
    $sql = "SELECT * FROM matches WHERE match_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $matchId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
?>
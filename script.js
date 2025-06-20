document.addEventListener('DOMContentLoaded', function () {
    const team1PlayerCountInput = document.getElementById('team1_player_count');
    const team2PlayerCountInput = document.getElementById('team2_player_count');
    const team1PlayerFieldsContainer = document.getElementById('team1_player_fields');
    const team2PlayerFieldsContainer = document.getElementById('team2_player_fields');

    function generatePlayerIdFields(teamNumber, playerCount) {
        const container = (teamNumber === 1) ? team1PlayerFieldsContainer : team2PlayerFieldsContainer;
        container.innerHTML = ''; // Clear existing fields

        if (playerCount > 0) { // Only generate and show if playerCount is greater than 0
            container.style.display = 'block'; // **Show the container**
            for (let i = 1; i <= playerCount; i++) {
                const playerDiv = document.createElement('div');
                playerDiv.classList.add('player-id-field');

                const label = document.createElement('label');
                label.setAttribute('for', `team${teamNumber}_player_id_${i}`);
                label.textContent = `Player ID ${i}:`;

                const input = document.createElement('input');
                input.type = 'text';
                input.id = `team${teamNumber}_player_id_${i}`;
                input.name = `team${teamNumber}_player_id_${i}`;

                const playerNameSpan = document.createElement('span'); // To display player name
                playerNameSpan.classList.add('player-name');

                input.addEventListener('focusout', function() { // Use focusout event
                    const playerId = this.value.trim();
                    if (playerId) {
                        fetchPlayerName(playerId, playerNameSpan);
                    } else {
                        playerNameSpan.textContent = ''; // Clear name if input is empty
                    }
                });

                playerDiv.appendChild(label);
                playerDiv.appendChild(input);
                playerDiv.appendChild(playerNameSpan); // Append span for name
                container.appendChild(playerDiv);
            }
        } else {
            container.style.display = 'none'; // Hide the container if playerCount is 0 or less
        }
    }

    function fetchPlayerName(playerId, nameSpanElement) {
        fetch('../includes/get_player_name.php?player_id=' + encodeURIComponent(playerId))
            .then(response => response.text())
            .then(playerName => {
                nameSpanElement.textContent = playerName;
                if (playerName === 'Player not found') {
                    nameSpanElement.classList.add('error'); // Add error class for styling
                } else {
                    nameSpanElement.classList.remove('error'); // Remove error class if name is found
                }
            })
            .catch(error => {
                console.error('Error fetching player name:', error);
                nameSpanElement.textContent = 'Error fetching name';
                nameSpanElement.classList.add('error');
            });
    }

    team1PlayerCountInput.addEventListener('change', function () {
        const playerCount = parseInt(this.value, 10);
        generatePlayerIdFields(1, playerCount);
    });

    team2PlayerCountInput.addEventListener('change', function () {
        const playerCount = parseInt(this.value, 10);
        generatePlayerIdFields(2, playerCount);
    });
});
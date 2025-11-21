// Variables du jeu
let flippedCards = [];
let canFlip = true;
let timerInterval = null;

// Démarrer le chronomètre
function startTimer() {
    const timerElement = document.getElementById('timer');
    if (!timerElement || timerInterval) return;

    const startTime = parseInt(timerElement.getAttribute('data-start-time'));
    
    function updateTimer() {
        const now = Math.floor(Date.now() / 1000);
        const elapsed = now - startTime;
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    // Mise à jour initiale
    updateTimer();
    
    // Mise à jour chaque seconde
    timerInterval = setInterval(updateTimer, 1000);
}

// Envoyer une requête AJAX pour retourner une carte
async function flipCard(cardId, cardElement) {
    try {
        const response = await fetch('/game/flip?cardId=' + cardId, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success) {
            // Mettre à jour l'affichage des statistiques
            if (result.moves !== undefined) {
                const movesElement = document.getElementById('moves');
                if (movesElement) {
                    movesElement.textContent = result.moves;
                }
            }

            if (result.matchedPairs !== undefined && result.totalPairs !== undefined) {
                const pairsElement = document.getElementById('pairs-found');
                if (pairsElement) {
                    pairsElement.textContent = `${result.matchedPairs} / ${result.totalPairs}`;
                }
            }

            // Gérer le résultat de la correspondance
            if (result.match) {
                if (result.match.isMatch) {
                    // Paire trouvée - attendre un peu puis marquer comme matched
                    setTimeout(() => {
                        result.match.cardIds.forEach(id => {
                            const card = document.querySelector(`[data-card-id="${id}"]`);
                            if (card) {
                                card.classList.add('matched');
                                // Désactiver le lien
                                const link = card.closest('a');
                                if (link) {
                                    link.style.pointerEvents = 'none';
                                }
                            }
                        });
                        flippedCards = [];
                        canFlip = true;

                        // Vérifier si le jeu est terminé
                        if (result.gameOver) {
                            setTimeout(() => {
                                // Arrêter le chronomètre
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                    timerInterval = null;
                                }
                            }, 500);
                        }
                    }, 1000);
                } else {
                    // Pas de correspondance - retourner les cartes
                    setTimeout(() => {
                        result.match.cardIds.forEach(id => {
                            const card = document.querySelector(`[data-card-id="${id}"]`);
                            if (card) {
                                card.classList.remove('flipped');
                                const inner = card.querySelector('.card-inner');
                                if (inner) {
                                    inner.style.transform = 'rotateY(0deg)';
                                }
                            }
                        });
                        flippedCards = [];
                        canFlip = true;
                    }, 1000);
                }
            }
        } else {
            console.error('Erreur:', result.message);
            // Retourner visuellement la carte en cas d'erreur
            cardElement.classList.remove('flipped');
            const inner = cardElement.querySelector('.card-inner');
            if (inner) {
                inner.style.transform = 'rotateY(0deg)';
            }
            canFlip = true;
        }
    } catch (error) {
        console.error('Erreur réseau:', error);
        // Retourner visuellement la carte en cas d'erreur
        cardElement.classList.remove('flipped');
        const inner = cardElement.querySelector('.card-inner');
        if (inner) {
            inner.style.transform = 'rotateY(0deg)';
        }
        canFlip = true;
    }
}

// Gérer le clic sur une carte
document.addEventListener('DOMContentLoaded', function() {
    // Démarrer le chronomètre
    startTimer();

    const cardLinks = document.querySelectorAll('.card-link');
    
    cardLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Empêcher le comportement par défaut du lien

            // Empêcher de cliquer si on ne peut pas retourner de cartes
            if (!canFlip) return;

            const card = this.querySelector('.card');
            if (!card) return;

            // Empêcher de cliquer sur une carte déjà retournée ou appariée
            if (card.classList.contains('flipped') || card.classList.contains('matched')) {
                return;
            }

            // Empêcher de retourner plus de 2 cartes
            if (flippedCards.length >= 2) return;

            // Retourner la carte visuellement
            const inner = card.querySelector('.card-inner');
            
            card.classList.add('flipped');
            if (inner) {
                inner.style.transform = 'rotateY(180deg)';
            }

            const cardId = card.getAttribute('data-card-id');
            flippedCards.push(cardId);

            // Si c'est la deuxième carte, bloquer et vérifier via le serveur
            if (flippedCards.length === 2) {
                canFlip = false;
            }

            // Envoyer au serveur
            flipCard(cardId, card);
        });
    });
});

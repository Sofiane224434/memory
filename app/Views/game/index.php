<?php
use App\Helpers\LoginHelper;
use App\Classes\Card;

if (!LoginHelper::isLogged()) {
    LoginHelper::redirectToLogin();
}
?>

<!-- Il faut pouvoir choisir le nombre de paires qui seront tirées au sort
aléatoirement.
Au minimum 3 paires différentes (6 cartes) au maximum 12 paires, -->

<main class="container mx-auto px-4 py-8">
    <h1 class="text-center text-4xl font-bold text-gray-800 mb-8">Bienvenue, <?= htmlspecialchars($user['username']) ?>
        !</h1>

    <section class="text-center my-8 p-5">
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <form method="GET" action="/game" class="flex items-center justify-center gap-4 flex-wrap">
                <label for="pairs" class="text-lg font-bold">Nombre de paires :</label>
                <select name="pairs" id="pairs"
                    class="px-4 py-2 text-base border-2 border-gray-800 rounded cursor-pointer">
                    <?php for ($i = 3; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= (isset($pairCount) && $pairCount == $i) ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <button type="submit"
                    class="px-5 py-2.5 text-base font-bold bg-green-600 text-white rounded cursor-pointer hover:bg-green-700 transition-colors">
                    Démarrer le jeu
                </button>
            </form>
            <?php if (isset($gameState) && !empty($cards)): ?>
                <a href="/game/reset"
                    class="px-5 py-2.5 text-base font-bold bg-green-600 text-white rounded cursor-pointer hover:bg-green-700 transition-colors no-underline">
                    Recommencer
                </a>
                <a href="/game/abandon"
                    class="px-5 py-2.5 text-base font-bold bg-green-600 text-white rounded cursor-pointer hover:bg-green-700 transition-colors no-underline">
                    Abandonner
                </a>
            <?php endif; ?>
        </div>
    </section>

    <?php if (isset($gameState) && !empty($cards)): ?>
        <section class="text-center my-8 text-lg bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
            <div class="flex justify-around items-center flex-wrap gap-4">
                <div>
                    <p class="text-gray-600">Nombre de paires</p>
                    <p class="text-2xl font-bold text-blue-600"><?= $pairCount ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Coups joués</p>
                    <p class="text-2xl font-bold text-green-600" id="moves"><?= $gameState['moves'] ?? 0 ?></p>
                </div>
                <div>
                    <p class="text-gray-600">Paires trouvées</p>
                    <p class="text-2xl font-bold text-purple-600" id="pairs-found">
                        <?= $gameState['matchedPairs'] ?? 0 ?> / <?= $pairCount ?>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">Temps écoulé</p>
                    <p class="text-2xl font-bold text-orange-600" id="timer"
                        data-start-time="<?= isset($gameState['startTime']) ? $gameState['startTime'] : time() ?>">
                        00:00
                    </p>
                </div>
            </div>
        </section>

        <?php
        // Vérifier si le jeu est terminé
        $gameOver = isset($gameState) && $gameState['matchedPairs'] === $gameState['totalPairs'];
        ?>

        <?php if ($gameOver): ?>
            <!-- Message de victoire -->
            <section class="bg-green-100 border-2 border-green-500 p-8 rounded-lg shadow-2xl text-center max-w-md mx-auto my-8">
                <h2 class="text-3xl font-bold text-green-600 mb-4">🎉 Félicitations ! 🎉</h2>
                <p class="text-xl mb-2">Vous avez trouvé toutes les paires !</p>
                <p class="text-gray-600 mb-1">Coups joués : <strong><?= $gameState['moves'] ?></strong></p>
                <p class="text-gray-600 mb-6">Temps :
                    <strong>
                        <?php
                        $elapsed = time() - $gameState['startTime'];
                        $minutes = floor($elapsed / 60);
                        $seconds = $elapsed % 60;
                        echo sprintf('%02d:%02d', $minutes, $seconds);
                        ?>
                    </strong>
                </p>
                <a href="/game/reset"
                    class="inline-block px-6 py-3 text-lg font-bold bg-green-600 text-white rounded cursor-pointer hover:bg-green-700 transition-colors no-underline">
                    Rejouer
                </a>
            </section>
        <?php endif; ?>

        <section
            class="grid grid-cols-[repeat(auto-fit,minmax(120px,1fr))] gap-4 max-w-4xl mx-auto my-5 p-5 bg-gray-200 rounded-xl shadow-lg">
            <?php foreach ($cards as $card): ?>
                <?php
                $isFlipped = $card->isFlipped();
                $isMatched = $card->isMatched();
                $canClick = !$isFlipped && !$isMatched && !$gameOver;
                $flippedCount = isset($gameState['flippedCards']) ? count($gameState['flippedCards']) : 0;
                $canClick = $canClick && $flippedCount < 2;
                ?>

                <?php if ($canClick): ?>
                    <a href="/game/flip?cardId=<?= $card->getId() ?>" class="card-link no-underline">
                    <?php endif; ?>

                    <div class="card aspect-[2/3] relative <?= $canClick ? 'cursor-pointer' : 'cursor-default' ?> <?= $isMatched ? 'matched' : '' ?>"
                        data-card-id="<?= $card->getId() ?>" style="perspective: 1000px;">
                        <div class="card-inner relative w-full h-full transition-transform duration-600"
                            style="transform-style: preserve-3d; <?= $isFlipped || $isMatched ? 'transform: rotateY(180deg);' : '' ?>">
                            <div class="card-front absolute w-full h-full rounded-lg overflow-hidden shadow-md bg-white"
                                style="backface-visibility: hidden;">
                                <img src="/assets/images/opbackcard.png" alt="Dos de carte" class="w-full h-full object-cover">
                            </div>
                            <div class="card-back absolute w-full h-full rounded-lg overflow-hidden shadow-md bg-white"
                                style="backface-visibility: hidden; transform: rotateY(180deg);">
                                <img src="/assets/images/<?= $card->getValue() ?>.webp" alt="<?= $card->getValue() ?>"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>

                    <?php if ($canClick): ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<script src="/assets/js/game.js"></script>
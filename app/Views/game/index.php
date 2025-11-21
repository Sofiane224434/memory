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
        <form method="GET" action="/game" class="flex items-center justify-center gap-4 flex-wrap">
            <label for="pairs" class="text-lg font-bold">Nombre de paires :</label>
            <select name="pairs" id="pairs" class="px-4 py-2 text-base border-2 border-gray-800 rounded cursor-pointer">
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
    </section>

    <?php if (isset($pairCount) && !empty($cards)): ?>
        <section class="text-center my-8 text-lg">
            <p>Nombre de paires : <strong><?= $pairCount ?></strong></p>
            <p>Nombre total de cartes : <strong><?= count($cards) ?></strong></p>
        </section>

        <section
            class="grid grid-cols-[repeat(auto-fit,minmax(120px,1fr))] gap-4 max-w-4xl mx-auto my-5 p-5 bg-gray-200 rounded-xl shadow-lg">
            <?php foreach ($cards as $card): ?>
                <div class="card aspect-[2/3] cursor-pointer relative [perspective:1000px]"
                    data-card-id="<?= $card->getId() ?>">
                    <div
                        class="card-inner relative w-full h-full transition-transform duration-600 [transform-style:preserve-3d]">
                        <div
                            class="card-front absolute w-full h-full [backface-visibility:hidden] rounded-lg overflow-hidden shadow-md bg-white">
                            <img src="/assets/images/opbackcard.png" alt="Dos de carte" class="w-full h-full object-cover">
                        </div>
                        <div
                            class="card-back absolute w-full h-full [backface-visibility:hidden] [transform:rotateY(180deg)] rounded-lg overflow-hidden shadow-md bg-white">
                            <img src="/assets/images/<?= $card->getValue() ?>.webp" alt="<?= $card->getValue() ?>"
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>

<script>
    // Empêcher de retourner plus de 2 cartes à la fois
    let flippedCards = [];
    document.querySelectorAll('.card').forEach(card => {
        card.addEventListener('click', function (e) {
            if (this.classList.contains('flipped') && flippedCards.includes(this)) {
                return; // Carte déjà retournée
            }

            if (flippedCards.length < 2) {
                const inner = this.querySelector('.card-inner');
                this.classList.add('flipped');
                inner.style.transform = 'rotateY(180deg)';
                flippedCards.push(this);

                if (flippedCards.length === 2) {
                    setTimeout(() => {
                        // Retourner les cartes après 1 seconde
                        flippedCards.forEach(card => {
                            card.classList.remove('flipped');
                            card.querySelector('.card-inner').style.transform = 'rotateY(0deg)';
                        });
                        flippedCards = [];
                    }, 1000);
                }
            }
        });
    });
</script>
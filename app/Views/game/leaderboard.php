<?php
use App\Helpers\LoginHelper;

if (!LoginHelper::isLogged()) {
    LoginHelper::redirectToLogin();
}
?>

<main class="container mx-auto px-4 py-8">
    <h1 class="text-center text-4xl font-bold text-gray-800 mb-8">🏆 Classement des meilleurs scores</h1>


    <?php if (empty($topScores)): ?>
        <div class="text-center bg-white p-8 rounded-lg shadow-md">
            <p class="text-xl text-gray-600">Aucune partie terminée pour le moment.</p>
            <p class="text-gray-500 mt-2">Soyez le premier à terminer une partie et à apparaître dans le classement !
            </p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-yellow-400 to-orange-400">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Rang
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                            Joueur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                            Paires</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Coups
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Temps
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Score
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($topScores as $index => $game): ?>
                        <?php
                        $isCurrentUser = $game['username'] === $user['username'];
                        $rowClass = $isCurrentUser ? 'bg-blue-50' : '';
                        $medalEmoji = match ($index) {
                            0 => '🥇',
                            1 => '🥈',
                            2 => '🥉',
                            default => ''
                        };
                        ?>
                        <tr class="<?= $rowClass ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                <?= $medalEmoji ?> #<?= $index + 1 ?>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-bold <?= $isCurrentUser ? 'text-blue-600' : 'text-gray-900' ?>">
                                <?= htmlspecialchars($game['username']) ?>
                                <?= $isCurrentUser ? '(Vous)' : '' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= $game['pairs_count'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= $game['moves'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php
                                $minutes = floor($game['time_seconds'] / 60);
                                $seconds = $game['time_seconds'] % 60;
                                echo sprintf('%02d:%02d', $minutes, $seconds);
                                ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                <?= $game['score'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    </div>
</main>
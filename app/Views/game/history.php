<?php
use App\Helpers\LoginHelper;

if (!LoginHelper::isLogged()) {
    LoginHelper::redirectToLogin();
}
?>

<main class="container mx-auto px-4 py-8">
    <h1 class="text-center text-4xl font-bold text-gray-800 mb-8">Historique de mes parties</h1>


    <?php if (empty($games)): ?>
        <div class="text-center bg-white p-8 rounded-lg shadow-md">
            <p class="text-xl text-gray-600">Aucune partie jouée pour le moment.</p>
            <a href="/game"
                class="inline-block mt-4 px-5 py-2.5 bg-green-600 text-white rounded hover:bg-green-700 transition-colors no-underline">
                Commencer à jouer
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                            Paires</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Coups
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Temps
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Score
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                            Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($games as $game): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= date('d/m/Y H:i', strtotime($game['created_at'])) ?>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                                <?= $game['score'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <?php
                                $statusClass = match ($game['status']) {
                                    'completed' => 'bg-green-100 text-green-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'abandoned' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                                $statusText = match ($game['status']) {
                                    'completed' => 'Terminée',
                                    'in_progress' => 'En cours',
                                    'abandoned' => 'Abandonnée',
                                    default => $game['status']
                                };
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    </div>
</main>
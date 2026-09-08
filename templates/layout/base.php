<?php
$currentPath = (string) (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$isSalleRoute = $currentPath === '/' || str_starts_with($currentPath, '/salles');
$isReservationRoute = str_starts_with($currentPath, '/reservations') && $currentPath !== '/reservations/create';
$isNewReservationRoute = $currentPath === '/reservations/create';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Réservations', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="/" aria-label="Réservations universitaires — accueil">
            <span class="brand-mark" aria-hidden="true">RU</span>
            <span class="brand-name">Réservations universitaires</span>
        </a>
        <nav class="site-nav" aria-label="Navigation principale">
            <a class="nav-link" href="/salles" <?= $isSalleRoute ? 'aria-current="page"' : '' ?>>Salles</a>
            <a class="nav-link" href="/reservations" <?= $isReservationRoute ? 'aria-current="page"' : '' ?>>Réservations</a>
            <a class="nav-link" href="/reservations/create" <?= $isNewReservationRoute ? 'aria-current="page"' : '' ?>>Nouvelle réservation</a>
        </nav>
    </div>
</header>
<main class="page-shell"><?= $content ?? '' ?></main>
</body>
</html>

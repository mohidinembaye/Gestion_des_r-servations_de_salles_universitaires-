<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Réservations', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="/">
            <span class="brand-mark">RU</span>
            <span>Réservations universitaires</span>
        </a>
    <nav>
        <a href="/salles">Salles</a>
        <a href="/reservations">Réservations</a>
        <a href="/reservations/create">Nouvelle réservation</a>
    </nav>
    </div>
</header>
<main class="page-shell"><?= $content ?? '' ?></main>
</body>
</html>

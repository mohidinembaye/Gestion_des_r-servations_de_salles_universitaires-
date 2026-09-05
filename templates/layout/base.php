<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Réservations', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <a href="/">Réservations universitaires</a>
    <nav>
        <a href="/salles">Salles</a>
        <a href="/reservations">Réservations</a>
        <a href="/reservations/create">Nouvelle réservation</a>
    </nav>
</header>
<main><?= $content ?? '' ?></main>
</body>
</html>

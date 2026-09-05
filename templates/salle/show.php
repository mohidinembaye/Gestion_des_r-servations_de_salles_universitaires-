<h1><?= htmlspecialchars((string) $salle->getNom(), ENT_QUOTES, 'UTF-8') ?></h1>
<p>Bâtiment : <?= htmlspecialchars((string) $salle->getBatiment(), ENT_QUOTES, 'UTF-8') ?></p>
<p>Capacité : <?= (int) $salle->getCapacite() ?></p>
<p>Type : <?= htmlspecialchars((string) $salle->getType(), ENT_QUOTES, 'UTF-8') ?></p>
<a href="/salles">Retour</a>

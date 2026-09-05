<h1>Salles</h1>
<a href="/salles/create">Ajouter une salle</a>
<ul>
<?php foreach ($salles as $salle): ?>
    <li><a href="/salles/<?= (int) $salle->getId() ?>"><?= htmlspecialchars((string) $salle->getNom(), ENT_QUOTES, 'UTF-8') ?></a></li>
<?php endforeach; ?>
</ul>

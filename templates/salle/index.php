<h1>Salles</h1>
<p>
    <a class="button" href="/salles/create">Ajouter une salle</a>
    <a class="button" href="/reservations/create">Réserver une salle</a>
</p>
<ul>
<?php foreach ($salles as $salle): ?>
    <li><a href="/salles/<?= (int) $salle->getId() ?>"><?= htmlspecialchars((string) $salle->getNom(), ENT_QUOTES, 'UTF-8') ?></a></li>
<?php endforeach; ?>
</ul>

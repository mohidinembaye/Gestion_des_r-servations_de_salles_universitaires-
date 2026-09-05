<div class="page-heading">
    <div>
        <p class="eyebrow">Campus universitaire</p>
        <h1>Salles disponibles</h1>
        <p class="intro">Consultez les espaces et préparez votre prochaine réservation.</p>
    </div>
    <div class="action-row">
    <a class="button" href="/salles/create">Ajouter une salle</a>
    <a class="button" href="/reservations/create">Réserver une salle</a>
</div>
</div>
<ul class="card-grid">
<?php foreach ($salles as $salle): ?>
    <li class="room-card">
        <div class="card-topline"><span class="room-type"><?= htmlspecialchars((string) $salle->getType(), ENT_QUOTES, 'UTF-8') ?></span><span class="status status-active">Active</span></div>
        <h2><a href="/salles/<?= (int) $salle->getId() ?>"><?= htmlspecialchars((string) $salle->getNom(), ENT_QUOTES, 'UTF-8') ?></a></h2>
        <p><?= htmlspecialchars((string) $salle->getBatiment(), ENT_QUOTES, 'UTF-8') ?></p>
        <strong><?= (int) $salle->getCapacite() ?> places</strong>
    </li>
<?php endforeach; ?>
</ul>

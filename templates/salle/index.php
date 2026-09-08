<div class="page-heading">
    <div class="heading-copy">
        <p class="eyebrow">Campus universitaire</p>
        <h1>Salles disponibles</h1>
        <p class="intro">Consultez les espaces et préparez votre prochaine réservation.</p>
    </div>
    <div class="heading-actions">
        <a class="button-link" href="/salles/create">Ajouter une salle</a>
        <a class="button" href="/reservations/create">Réserver une salle</a>
    </div>
</div>
<?php if (($_GET['created'] ?? '') === '1'): ?>
    <div class="feedback feedback-success" role="status">
        <span class="feedback-mark" aria-hidden="true">✓</span>
        <div>
            <strong>Salle créée avec succès.</strong>
            <p>La nouvelle salle apparaît ci-dessous et peut être réservée.</p>
        </div>
    </div>
<?php endif; ?>
<ul class="card-grid" aria-label="Salles disponibles">
<?php foreach ($salles as $salle): ?>
    <li class="room-card">
        <a class="room-card-link" href="/salles/<?= (int) $salle->getId() ?>">
            <div class="card-topline">
                <span class="room-type"><?= htmlspecialchars((string) $salle->getType(), ENT_QUOTES, 'UTF-8') ?></span>
                <span class="status status-active">Active</span>
            </div>
            <h2><?= htmlspecialchars((string) $salle->getNom(), ENT_QUOTES, 'UTF-8') ?></h2>
            <p class="room-building"><?= htmlspecialchars((string) $salle->getBatiment(), ENT_QUOTES, 'UTF-8') ?></p>
            <div class="room-capacity">
                <strong><?= (int) $salle->getCapacite() ?></strong>
                <span>places</span>
            </div>
        </a>
    </li>
<?php endforeach; ?>
</ul>

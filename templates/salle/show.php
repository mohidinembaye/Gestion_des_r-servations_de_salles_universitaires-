<?php $roomName = (string) $salle->getNom(); ?>
<div class="detail-shell">
    <div class="detail-header">
        <div>
            <p class="eyebrow">Fiche salle</p>
            <h1><?= htmlspecialchars($roomName, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="detail-lead">Un espace du campus prêt à accueillir vos activités.</p>
        </div>
        <span class="status status-active">Active</span>
    </div>
    <dl class="detail-list">
        <div class="detail-item detail-item-feature">
            <dt>Capacité</dt>
            <dd><strong><?= (int) $salle->getCapacite() ?></strong> places</dd>
        </div>
        <div class="detail-item">
            <dt>Bâtiment</dt>
            <dd><?= htmlspecialchars((string) $salle->getBatiment(), ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
        <div class="detail-item">
            <dt>Type</dt>
            <dd><?= htmlspecialchars((string) $salle->getType(), ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
    </dl>
    <div class="detail-actions">
        <a class="button" href="/reservations/create">Réserver une salle</a>
        <a class="button-link" href="/salles">Retour aux salles</a>
    </div>
</div>

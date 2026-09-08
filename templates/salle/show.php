<?php
if ($salle === null): ?>
<div class="state-panel">
    <p class="eyebrow">Navigation</p>
    <h1>Salle introuvable</h1>
    <p>La salle demandée n’existe pas.</p>
    <a class="button-link" href="/salles">Retour aux salles</a>
</div>
<?php return; endif;

$roomName = (string) $salle->getNom();
$isActive = (bool) $salle->isActive();
?>
<?php if (($_GET['updated'] ?? '') === '1'): ?>
    <div class="feedback feedback-success" role="status">
        <span class="feedback-mark" aria-hidden="true">✓</span>
        <div>
            <strong>Salle modifiée avec succès.</strong>
            <p>Les modifications apportées à la salle ont bien été enregistrées.</p>
        </div>
    </div>
<?php endif; ?>
<div class="detail-shell">
    <div class="detail-header">
        <div>
            <p class="eyebrow">Fiche salle</p>
            <h1><?= htmlspecialchars($roomName, ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="detail-lead">Un espace du campus prêt à accueillir vos activités.</p>
        </div>
        <span class="status <?= $isActive ? 'status-active' : 'status-cancelled' ?>"><?= $isActive ? 'Active' : 'Inactive' ?></span>
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
        <a class="button-link" href="/salles/<?= (int) $salle->getId() ?>/edit">Modifier la salle</a>
        <a class="button-link" href="/salles">Retour aux salles</a>
    </div>
</div>

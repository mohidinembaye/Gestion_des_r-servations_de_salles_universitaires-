<?php
$salle = $reservation->getSalle();
$roomName = (string) ($salle?->getNom() ?? 'Salle inconnue');
$dateDebut = $reservation->getDateDebut();
$dateFin = $reservation->getDateFin();
$isConfirmed = $reservation->getStatut() === 'confirmée';
?>
<?php if (($_GET['updated'] ?? '') === '1'): ?>
    <div class="feedback feedback-success" role="status">
        <span class="feedback-mark" aria-hidden="true">✓</span>
        <div>
            <strong>Réservation modifiée avec succès.</strong>
            <p>Les modifications apportées au créneau ont bien été enregistrées.</p>
        </div>
    </div>
<?php endif; ?>
<div class="detail-shell">
    <div class="detail-header">
        <div>
            <p class="eyebrow">Fiche réservation</p>
            <h1>Réservation #<?= (int) $reservation->getId() ?></h1>
            <p class="detail-lead"><?= htmlspecialchars($roomName, ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <span class="status <?= $isConfirmed ? 'status-active' : 'status-cancelled' ?>"><?= htmlspecialchars((string) $reservation->getStatut(), ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <div class="detail-summary" aria-label="Créneau réservé">
        <div class="summary-item summary-item-primary">
            <span>Salle</span>
            <strong><?= htmlspecialchars($roomName, ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div class="summary-item">
            <span>Début</span>
            <strong><?= htmlspecialchars($dateDebut?->format('d/m/Y H:i') ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div class="summary-item">
            <span>Fin</span>
            <strong><?= htmlspecialchars($dateFin?->format('d/m/Y H:i') ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
    </div>
    <dl class="detail-list">
        <div class="detail-item">
            <dt>Responsable</dt>
            <dd><?= htmlspecialchars((string) $reservation->getResponsable(), ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
        <div class="detail-item">
            <dt>Email</dt>
            <dd><a href="mailto:<?= htmlspecialchars((string) $reservation->getEmail(), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $reservation->getEmail(), ENT_QUOTES, 'UTF-8') ?></a></dd>
        </div>
        <div class="detail-item">
            <dt>Motif</dt>
            <dd><?= htmlspecialchars((string) $reservation->getMotif(), ENT_QUOTES, 'UTF-8') ?></dd>
        </div>
    </dl>
    <div class="detail-actions">
        <?php if ($isConfirmed): ?>
            <a class="button-link" href="/reservations/<?= (int) $reservation->getId() ?>/edit">Modifier la réservation</a>
            <form class="detail-cancel-form" method="post" action="/reservations/<?= (int) $reservation->getId() ?>/cancel">
                <button class="button-danger" type="submit">Annuler la réservation</button>
            </form>
        <?php endif; ?>
        <a class="button-link" href="/reservations">Retour aux réservations</a>
    </div>
</div>

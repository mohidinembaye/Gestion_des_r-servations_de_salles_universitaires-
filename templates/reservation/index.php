<div class="page-heading">
    <div>
        <p class="eyebrow">Planning des espaces</p>
        <h1>Réservations</h1>
        <p class="intro">Suivez les créneaux confirmés et les demandes de votre campus.</p>
    </div>
    <a class="button" href="/reservations/create">Créer une réservation</a>
</div>
<?php if ($reservations->isEmpty()): ?>
    <div class="empty-state"><strong>Aucune réservation enregistrée.</strong><p>Votre planning est libre pour le moment.</p></div>
<?php else: ?>
    <ul class="booking-list">
    <?php foreach ($reservations as $reservation): ?>
        <?php $salle = $reservation->getSalle(); ?>
        <li class="booking-row">
            <div class="booking-date"><strong><?= htmlspecialchars($reservation->getDateDebut()?->format('d/m') ?? '', ENT_QUOTES, 'UTF-8') ?></strong><span><?= htmlspecialchars($reservation->getDateDebut()?->format('H:i') ?? '', ENT_QUOTES, 'UTF-8') ?></span></div>
            <div class="booking-main">
                <a href="/reservations/<?= (int) $reservation->getId() ?>"> <?= htmlspecialchars((string) ($salle?->getNom() ?? 'Salle inconnue'), ENT_QUOTES, 'UTF-8') ?></a>
                <p><?= htmlspecialchars((string) $reservation->getResponsable(), ENT_QUOTES, 'UTF-8') ?> · <?= htmlspecialchars($reservation->getDateFin()?->format('H:i') ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <span class="status <?= $reservation->getStatut() === 'confirmée' ? 'status-active' : 'status-cancelled' ?>"><?= htmlspecialchars((string) $reservation->getStatut(), ENT_QUOTES, 'UTF-8') ?></span>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

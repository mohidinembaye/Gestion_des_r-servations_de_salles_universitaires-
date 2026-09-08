<div class="page-heading">
    <div class="heading-copy">
        <p class="eyebrow">Planning des espaces</p>
        <h1>Réservations</h1>
        <p class="intro">Suivez les créneaux confirmés et les demandes de votre campus.</p>
    </div>
    <div class="heading-actions">
        <a class="button" href="/reservations/create">Créer une réservation</a>
    </div>
</div>
<?php if ($reservations->isEmpty()): ?>
    <div class="empty-state">
        <span class="empty-state-mark" aria-hidden="true">—</span>
        <strong>Aucune réservation enregistrée.</strong>
        <p>Votre planning est libre pour le moment.</p>
    </div>
<?php else: ?>
    <?php $currentDateKey = null; $hasOpenDateGroup = false; ?>
    <ul class="booking-list" aria-label="Planning des réservations">
    <?php foreach ($reservations as $reservation): ?>
        <?php
        $salle = $reservation->getSalle();
        $dateDebut = $reservation->getDateDebut();
        $dateFin = $reservation->getDateFin();
        $dateKey = $dateDebut?->format('Y-m-d') ?? 'sans-date';
        $roomName = (string) ($salle?->getNom() ?? 'Salle inconnue');
        ?>
        <?php if ($dateKey !== $currentDateKey): ?>
            <?php if ($hasOpenDateGroup): ?></ul></li><?php endif; ?>
            <?php $currentDateKey = $dateKey; $hasOpenDateGroup = true; ?>
            <li class="booking-day">
                <h2 class="booking-day-heading">
                    <span class="sr-only">Réservations du </span><?= htmlspecialchars($dateDebut?->format('d/m') ?? '—', ENT_QUOTES, 'UTF-8') ?>
                </h2>
                <ul class="booking-day-list">
        <?php endif; ?>
                    <li class="booking-row">
                        <time class="booking-time" datetime="<?= htmlspecialchars($dateDebut?->format('c') ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <strong><?= htmlspecialchars($dateDebut?->format('H:i') ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
                            <span>jusqu’à <?= htmlspecialchars($dateFin?->format('H:i') ?? '—', ENT_QUOTES, 'UTF-8') ?></span>
                        </time>
                        <div class="booking-main">
                            <a class="booking-room" href="/reservations/<?= (int) $reservation->getId() ?>"><?= htmlspecialchars($roomName, ENT_QUOTES, 'UTF-8') ?></a>
                            <p><span><?= htmlspecialchars((string) $reservation->getResponsable(), ENT_QUOTES, 'UTF-8') ?></span><span class="booking-separator" aria-hidden="true">·</span><span>réservation <?= (int) $reservation->getId() ?></span></p>
                        </div>
                        <div class="booking-actions">
                            <span class="status <?= $reservation->getStatut() === 'confirmée' ? 'status-active' : 'status-cancelled' ?>"><?= htmlspecialchars((string) $reservation->getStatut(), ENT_QUOTES, 'UTF-8') ?></span>
                            <?php if ($reservation->getStatut() === 'confirmée'): ?>
                                <a class="button-link" href="/reservations/<?= (int) $reservation->getId() ?>/edit">Modifier</a>
                                <form class="booking-cancel-form" method="post" action="/reservations/<?= (int) $reservation->getId() ?>/cancel">
                                    <button class="button-danger button-compact" type="submit" aria-label="Annuler la réservation de <?= htmlspecialchars($roomName, ENT_QUOTES, 'UTF-8') ?>">Annuler</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </li>
    <?php endforeach; ?>
    <?php if ($hasOpenDateGroup): ?></ul></li><?php endif; ?>
    </ul>
<?php endif; ?>

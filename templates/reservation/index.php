<h1>Réservations</h1>
<p><a class="button" href="/reservations/create">Créer une réservation</a></p>
<?php if ($reservations->isEmpty()): ?>
    <p>Aucune réservation enregistrée.</p>
<?php else: ?>
    <ul>
    <?php foreach ($reservations as $reservation): ?>
        <?php $salle = $reservation->getSalle(); ?>
        <li>
            <a href="/reservations/<?= (int) $reservation->getId() ?>">
                Réservation #<?= (int) $reservation->getId() ?>
            </a>
            <p><?= htmlspecialchars((string) $reservation->getResponsable(), ENT_QUOTES, 'UTF-8') ?>
                - <?= htmlspecialchars((string) ($salle?->getNom() ?? 'Salle inconnue'), ENT_QUOTES, 'UTF-8') ?></p>
            <p><?= htmlspecialchars($reservation->getDateDebut()?->format('d/m/Y H:i') ?? '', ENT_QUOTES, 'UTF-8') ?>
                à <?= htmlspecialchars($reservation->getDateFin()?->format('d/m/Y H:i') ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <strong><?= htmlspecialchars((string) $reservation->getStatut(), ENT_QUOTES, 'UTF-8') ?></strong>
        </li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

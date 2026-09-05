<h1>Réservations</h1>
<a href="/reservations/create">Créer une réservation</a>
<ul>
<?php foreach ($reservations as $reservation): ?>
    <li><a href="/reservations/<?= (int) $reservation->getId() ?>">Réservation #<?= (int) $reservation->getId() ?></a></li>
<?php endforeach; ?>
</ul>

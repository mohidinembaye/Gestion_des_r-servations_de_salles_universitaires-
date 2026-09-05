<h1>Réservation #<?= (int) $reservation->getId() ?></h1>
<p>Responsable : <?= htmlspecialchars((string) $reservation->getResponsable(), ENT_QUOTES, 'UTF-8') ?></p>
<p>Email : <?= htmlspecialchars((string) $reservation->getEmail(), ENT_QUOTES, 'UTF-8') ?></p>
<p>Motif : <?= htmlspecialchars((string) $reservation->getMotif(), ENT_QUOTES, 'UTF-8') ?></p>
<p>Statut : <?= htmlspecialchars((string) $reservation->getStatut(), ENT_QUOTES, 'UTF-8') ?></p>
<form method="post" action="/reservations/<?= (int) $reservation->getId() ?>/cancel"><button type="submit">Annuler</button></form>

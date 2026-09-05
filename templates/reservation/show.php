<h1>Réservation #<?= (int) $reservation->getId() ?></h1>
<?php $salle = $reservation->getSalle(); ?>
<p>Salle : <?= htmlspecialchars((string) ($salle?->getNom() ?? 'Salle inconnue'), ENT_QUOTES, 'UTF-8') ?></p>
<p>Responsable : <?= htmlspecialchars((string) $reservation->getResponsable(), ENT_QUOTES, 'UTF-8') ?></p>
<p>Email : <?= htmlspecialchars((string) $reservation->getEmail(), ENT_QUOTES, 'UTF-8') ?></p>
<p>Motif : <?= htmlspecialchars((string) $reservation->getMotif(), ENT_QUOTES, 'UTF-8') ?></p>
<p>Début : <?= htmlspecialchars($reservation->getDateDebut()?->format('d/m/Y H:i') ?? '', ENT_QUOTES, 'UTF-8') ?></p>
<p>Fin : <?= htmlspecialchars($reservation->getDateFin()?->format('d/m/Y H:i') ?? '', ENT_QUOTES, 'UTF-8') ?></p>
<p>Statut : <?= htmlspecialchars((string) $reservation->getStatut(), ENT_QUOTES, 'UTF-8') ?></p>
<?php if ($reservation->getStatut() === 'confirmée'): ?>
	<form method="post" action="/reservations/<?= (int) $reservation->getId() ?>/cancel">
		<button type="submit">Annuler la réservation</button>
	</form>
<?php endif; ?>
<p><a href="/reservations">Retour aux réservations</a></p>

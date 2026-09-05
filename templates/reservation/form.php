<h1>Créer une réservation</h1>
<form method="post" action="/reservations">
<label>Salle <select name="salle_id">
<?php foreach ($salles as $salle): ?>
<option value="<?= (int) $salle->getId() ?>"><?= htmlspecialchars((string) $salle->getNom(), ENT_QUOTES, 'UTF-8') ?></option>
<?php endforeach; ?>
</select></label>
<?= isset($errors['salle_id']) ? '<p>' . htmlspecialchars($errors['salle_id'], ENT_QUOTES, 'UTF-8') . '</p>' : '' ?>
<label>Responsable <input name="responsable" value="<?= htmlspecialchars((string) ($values['responsable'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<?= isset($errors['responsable']) ? '<p>' . htmlspecialchars($errors['responsable'], ENT_QUOTES, 'UTF-8') . '</p>' : '' ?>
<label>Email <input type="email" name="email" value="<?= htmlspecialchars((string) ($values['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<?= isset($errors['email']) ? '<p>' . htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') . '</p>' : '' ?>
<label>Motif <input name="motif" value="<?= htmlspecialchars((string) ($values['motif'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<?= isset($errors['motif']) ? '<p>' . htmlspecialchars($errors['motif'], ENT_QUOTES, 'UTF-8') . '</p>' : '' ?>
<label>Début <input type="datetime-local" name="date_debut"></label>
<label>Fin <input type="datetime-local" name="date_fin"></label>
<button type="submit">Réserver</button>
</form>

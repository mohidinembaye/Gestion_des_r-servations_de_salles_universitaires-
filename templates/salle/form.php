<h1>Ajouter une salle</h1>
<form method="post" action="/salles">
<label>Nom <input name="nom" value="<?= htmlspecialchars((string) ($values['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<?= isset($errors['nom']) ? '<p>' . htmlspecialchars($errors['nom'], ENT_QUOTES, 'UTF-8') . '</p>' : '' ?>
<label>Bâtiment <input name="batiment" value="<?= htmlspecialchars((string) ($values['batiment'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<?= isset($errors['batiment']) ? '<p>' . htmlspecialchars($errors['batiment'], ENT_QUOTES, 'UTF-8') . '</p>' : '' ?>
<label>Capacité <input type="number" name="capacite" value="<?= htmlspecialchars((string) ($values['capacite'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<?= isset($errors['capacite']) ? '<p>' . htmlspecialchars($errors['capacite'], ENT_QUOTES, 'UTF-8') . '</p>' : '' ?>
<label>Type <input name="type" value="<?= htmlspecialchars((string) ($values['type'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<?= isset($errors['type']) ? '<p>' . htmlspecialchars($errors['type'], ENT_QUOTES, 'UTF-8') . '</p>' : '' ?>
<label>Active <input type="checkbox" name="active" value="1" checked></label>
<button type="submit">Enregistrer</button>
</form>

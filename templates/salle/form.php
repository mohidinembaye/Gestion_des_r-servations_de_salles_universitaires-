<?php
$isActive = !array_key_exists('active', $values) || in_array((string) ($values['active'] ?? ''), ['1', 'true', 'on', 'yes'], true);
?>
<div class="form-shell">
    <div class="form-heading">
        <p class="eyebrow">Gestion des espaces</p>
        <h1>Ajouter une salle</h1>
        <p class="intro">Décrivez l’espace pour le rendre disponible dans le catalogue du campus.</p>
    </div>
    <form class="form-stack" method="post" action="/salles">
        <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Champs obligatoires</p>
        <div class="field-group">
            <label for="nom">Nom <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="nom" name="nom" value="<?= htmlspecialchars((string) ($values['nom'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required maxlength="100" autocomplete="off" aria-invalid="<?= isset($errors['nom']) ? 'true' : 'false' ?>" <?= isset($errors['nom']) ? 'aria-describedby="nom-error"' : '' ?>>
            <?php if (isset($errors['nom'])): ?><p id="nom-error" class="field-error"><?= htmlspecialchars($errors['nom'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="batiment">Bâtiment <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="batiment" name="batiment" value="<?= htmlspecialchars((string) ($values['batiment'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required maxlength="100" autocomplete="organization">
            <?php if (isset($errors['batiment'])): ?><p id="batiment-error" class="field-error"><?= htmlspecialchars($errors['batiment'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="capacite">Capacité <span class="required-mark" aria-hidden="true">*</span></label>
            <span class="field-help">Indiquez le nombre maximal de places, entre 1 et 1000.</span>
            <input id="capacite" type="number" name="capacite" value="<?= htmlspecialchars((string) ($values['capacite'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required min="1" max="1000" inputmode="numeric" aria-invalid="<?= isset($errors['capacite']) ? 'true' : 'false' ?>" <?= isset($errors['capacite']) ? 'aria-describedby="capacite-error"' : '' ?>>
            <?php if (isset($errors['capacite'])): ?><p id="capacite-error" class="field-error"><?= htmlspecialchars($errors['capacite'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="type">Type <span class="required-mark" aria-hidden="true">*</span></label>
            <select id="type" name="type" required aria-invalid="<?= isset($errors['type']) ? 'true' : 'false' ?>" <?= isset($errors['type']) ? 'aria-describedby="type-error"' : '' ?>>
            <?php foreach (['cours' => 'Cours', 'informatique' => 'Informatique', 'laboratoire' => 'Laboratoire', 'amphitheatre' => 'Amphithéâtre', 'reunion' => 'Réunion'] as $valeur => $libelle): ?>
                <option value="<?= htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8') ?>" <?= ($values['type'] ?? '') === $valeur ? 'selected' : '' ?>><?= htmlspecialchars($libelle, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
            </select>
            <?php if (isset($errors['type'])): ?><p id="type-error" class="field-error"><?= htmlspecialchars($errors['type'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="checkbox-field">
            <input id="active" type="checkbox" name="active" value="1" <?= $isActive ? 'checked' : '' ?>>
            <label for="active">Active <span class="field-help">La salle reste visible dans le catalogue.</span></label>
        </div>
        <?php if (isset($errors['active'])): ?><p class="field-error"><?= htmlspecialchars($errors['active'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        <div class="form-actions">
            <button class="button" type="submit">Enregistrer</button>
            <a class="button-link" href="/salles">Annuler</a>
        </div>
    </form>
</div>

<?php
if (array_key_exists('salle', get_defined_vars()) && $salle === null): ?>
<div class="state-panel">
    <p class="eyebrow">Navigation</p>
    <h1>Salle introuvable</h1>
    <p>La salle demandée n’existe pas.</p>
    <a class="button-link" href="/salles">Retour aux salles</a>
</div>
<?php return; endif;

$isEdit = isset($salle) && $salle !== null;

$nom = (string) ($values['nom'] ?? ($isEdit ? $salle->getNom() : ''));
$batiment = (string) ($values['batiment'] ?? ($isEdit ? $salle->getBatiment() : ''));
$capacite = (string) ($values['capacite'] ?? ($isEdit ? (string) $salle->getCapacite() : ''));
$type = (string) ($values['type'] ?? ($isEdit ? $salle->getType() : 'cours'));

if (!empty($values)) {
    $isActive = in_array((string) ($values['active'] ?? ''), ['1', 'true', 'on', 'yes'], true);
} elseif ($isEdit) {
    $isActive = (bool) $salle->isActive();
} else {
    $isActive = true;
}

$action = $isEdit ? '/salles/' . (int) $salle->getId() . '/edit' : '/salles';
$headingTitle = $isEdit ? 'Modifier la salle' : 'Ajouter une salle';
$headingIntro = $isEdit
    ? 'Modifiez les informations de l’espace ci-dessous.'
    : 'Décrivez l’espace pour le rendre disponible dans le catalogue du campus.';
$cancelUrl = $isEdit ? '/salles/' . (int) $salle->getId() : '/salles';
?>
<div class="form-shell">
    <div class="form-heading">
        <p class="eyebrow">Gestion des espaces</p>
        <h1><?= htmlspecialchars($headingTitle, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="intro"><?= htmlspecialchars($headingIntro, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <form class="form-stack" method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" novalidate>
        <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Champs obligatoires</p>
        <div class="field-group">
            <label for="nom">Nom <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="nom" name="nom" value="<?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?>" autocomplete="off" aria-invalid="<?= isset($errors['nom']) ? 'true' : 'false' ?>" <?= isset($errors['nom']) ? 'aria-describedby="nom-error"' : '' ?>>
            <?php if (isset($errors['nom'])): ?><p id="nom-error" class="field-error"><?= htmlspecialchars($errors['nom'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="batiment">Bâtiment <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="batiment" name="batiment" value="<?= htmlspecialchars($batiment, ENT_QUOTES, 'UTF-8') ?>" autocomplete="organization" aria-invalid="<?= isset($errors['batiment']) ? 'true' : 'false' ?>" <?= isset($errors['batiment']) ? 'aria-describedby="batiment-error"' : '' ?>>
            <?php if (isset($errors['batiment'])): ?><p id="batiment-error" class="field-error"><?= htmlspecialchars($errors['batiment'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="capacite">Capacité <span class="required-mark" aria-hidden="true">*</span></label>
            <span class="field-help">Indiquez le nombre maximal de places, entre 1 et 1000.</span>
            <input id="capacite" name="capacite" value="<?= htmlspecialchars($capacite, ENT_QUOTES, 'UTF-8') ?>" aria-invalid="<?= isset($errors['capacite']) ? 'true' : 'false' ?>" <?= isset($errors['capacite']) ? 'aria-describedby="capacite-error"' : '' ?>>
            <?php if (isset($errors['capacite'])): ?><p id="capacite-error" class="field-error"><?= htmlspecialchars($errors['capacite'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="type">Type <span class="required-mark" aria-hidden="true">*</span></label>
            <select id="type" name="type" aria-invalid="<?= isset($errors['type']) ? 'true' : 'false' ?>" <?= isset($errors['type']) ? 'aria-describedby="type-error"' : '' ?>>
            <?php foreach (['cours' => 'Cours', 'informatique' => 'Informatique', 'laboratoire' => 'Laboratoire', 'amphitheatre' => 'Amphithéâtre', 'reunion' => 'Réunion'] as $valeur => $libelle): ?>
                <option value="<?= htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8') ?>" <?= $type === $valeur ? 'selected' : '' ?>><?= htmlspecialchars($libelle, ENT_QUOTES, 'UTF-8') ?></option>
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
            <button class="button" type="submit"><?= $isEdit ? 'Enregistrer les modifications' : 'Enregistrer' ?></button>
            <a class="button-link" href="<?= htmlspecialchars($cancelUrl, ENT_QUOTES, 'UTF-8') ?>">Annuler</a>
        </div>
    </form>
</div>
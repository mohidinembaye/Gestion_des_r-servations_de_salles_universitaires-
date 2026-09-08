<?php
if (array_key_exists('reservation', get_defined_vars()) && $reservation === null): ?>
<div class="state-panel">
    <p class="eyebrow">Navigation</p>
    <h1>Réservation introuvable</h1>
    <p>La réservation demandée n’existe pas.</p>
    <a class="button-link" href="/reservations">Retour aux réservations</a>
</div>
<?php return; endif;

$isEdit = isset($reservation) && $reservation !== null;

$salleIdVal = (string) ($values['salle_id'] ?? ($isEdit ? (string) $reservation->getSalleId() : ''));
$responsableVal = (string) ($values['responsable'] ?? ($isEdit ? $reservation->getResponsable() : ''));
$emailVal = (string) ($values['email'] ?? ($isEdit ? $reservation->getEmail() : ''));
$motifVal = (string) ($values['motif'] ?? ($isEdit ? $reservation->getMotif() : ''));

$dateDebutVal = (string) ($values['date_debut'] ?? ($isEdit ? ($reservation->getDateDebut()?->format('Y-m-d\TH:i') ?? '') : ''));
$dateFinVal = (string) ($values['date_fin'] ?? ($isEdit ? ($reservation->getDateFin()?->format('Y-m-d\TH:i') ?? '') : ''));

$action = $isEdit ? '/reservations/' . (int) $reservation->getId() . '/edit' : '/reservations';
$headingTitle = $isEdit ? 'Modifier la réservation' : 'Créer une réservation';
$headingIntro = $isEdit
    ? 'Modifiez les informations ou le créneau de votre réservation.'
    : 'Choisissez une salle et une période disponible.';
$cancelUrl = $isEdit ? '/reservations/' . (int) $reservation->getId() : '/reservations';
$submitLabel = $isEdit ? 'Enregistrer les modifications' : 'Réserver';
?>
<div class="form-shell">
    <div class="form-heading">
        <p class="eyebrow">Planning des espaces</p>
        <h1><?= htmlspecialchars($headingTitle, ENT_QUOTES, 'UTF-8') ?></h1>
        <p class="intro"><?= htmlspecialchars($headingIntro, ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <form class="form-stack" method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" novalidate>
        <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Champs obligatoires</p>
        <div class="field-group">
            <label for="salle_id">Salle <span class="required-mark" aria-hidden="true">*</span></label>
            <select id="salle_id" name="salle_id" aria-invalid="<?= isset($errors['salle_id']) ? 'true' : 'false' ?>" <?= isset($errors['salle_id']) ? 'aria-describedby="salle-id-error"' : '' ?>>
            <?php foreach ($salles as $salle): ?>
                <option value="<?= (int) $salle->getId() ?>" <?= $salleIdVal === (string) $salle->getId() ? 'selected' : '' ?>><?= htmlspecialchars((string) $salle->getNom(), ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
            </select>
            <?php if (isset($errors['salle_id'])): ?><p id="salle-id-error" class="field-error"><?= htmlspecialchars($errors['salle_id'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="responsable">Responsable <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="responsable" name="responsable" value="<?= htmlspecialchars($responsableVal, ENT_QUOTES, 'UTF-8') ?>" autocomplete="name" aria-invalid="<?= isset($errors['responsable']) ? 'true' : 'false' ?>" <?= isset($errors['responsable']) ? 'aria-describedby="responsable-error"' : '' ?>>
            <?php if (isset($errors['responsable'])): ?><p id="responsable-error" class="field-error"><?= htmlspecialchars($errors['responsable'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="email">Email <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="email" name="email" value="<?= htmlspecialchars($emailVal, ENT_QUOTES, 'UTF-8') ?>" autocomplete="email" aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>" <?= isset($errors['email']) ? 'aria-describedby="email-error"' : '' ?>>
            <?php if (isset($errors['email'])): ?><p id="email-error" class="field-error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="motif">Motif <span class="required-mark" aria-hidden="true">*</span></label>
            <textarea id="motif" name="motif" rows="3" aria-invalid="<?= isset($errors['motif']) ? 'true' : 'false' ?>" <?= isset($errors['motif']) ? 'aria-describedby="motif-error"' : '' ?>><?= htmlspecialchars($motifVal, ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php if (isset($errors['motif'])): ?><p id="motif-error" class="field-error"><?= htmlspecialchars($errors['motif'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="date_debut">Début <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="date_debut" type="datetime-local" name="date_debut" value="<?= htmlspecialchars($dateDebutVal, ENT_QUOTES, 'UTF-8') ?>" aria-invalid="<?= isset($errors['date_debut']) ? 'true' : 'false' ?>" <?= isset($errors['date_debut']) ? 'aria-describedby="date-debut-error"' : '' ?>>
            <?php if (isset($errors['date_debut'])): ?><p id="date-debut-error" class="field-error"><?= htmlspecialchars($errors['date_debut'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="date_fin">Fin <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="date_fin" type="datetime-local" name="date_fin" value="<?= htmlspecialchars($dateFinVal, ENT_QUOTES, 'UTF-8') ?>" aria-invalid="<?= isset($errors['date_fin']) ? 'true' : 'false' ?>" <?= isset($errors['date_fin']) ? 'aria-describedby="date-fin-error"' : '' ?>>
            <?php if (isset($errors['date_fin'])): ?><p id="date-fin-error" class="field-error"><?= htmlspecialchars($errors['date_fin'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="form-actions">
            <button class="button" type="submit"><?= htmlspecialchars($submitLabel, ENT_QUOTES, 'UTF-8') ?></button>
            <a class="button-link" href="<?= htmlspecialchars($cancelUrl, ENT_QUOTES, 'UTF-8') ?>">Annuler</a>
        </div>
    </form>
</div>
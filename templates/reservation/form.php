<div class="form-shell">
    <div class="form-heading">
        <p class="eyebrow">Planning des espaces</p>
        <h1>Créer une réservation</h1>
        <p class="intro">Choisissez une salle et une période disponible.</p>
    </div>
    <form class="form-stack" method="post" action="/reservations">
        <p class="form-note"><span class="required-mark" aria-hidden="true">*</span> Champs obligatoires</p>
        <div class="field-group">
            <label for="salle_id">Salle <span class="required-mark" aria-hidden="true">*</span></label>
            <select id="salle_id" name="salle_id" required aria-invalid="<?= isset($errors['salle_id']) ? 'true' : 'false' ?>" <?= isset($errors['salle_id']) ? 'aria-describedby="salle-id-error"' : '' ?>>
            <?php foreach ($salles as $salle): ?>
                <option value="<?= (int) $salle->getId() ?>" <?= (string) ($values['salle_id'] ?? '') === (string) $salle->getId() ? 'selected' : '' ?>><?= htmlspecialchars((string) $salle->getNom(), ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
            </select>
            <?php if (isset($errors['salle_id'])): ?><p id="salle-id-error" class="field-error"><?= htmlspecialchars($errors['salle_id'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="responsable">Responsable <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="responsable" name="responsable" value="<?= htmlspecialchars((string) ($values['responsable'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required minlength="2" maxlength="120" autocomplete="name" aria-invalid="<?= isset($errors['responsable']) ? 'true' : 'false' ?>" <?= isset($errors['responsable']) ? 'aria-describedby="responsable-error"' : '' ?>>
            <?php if (isset($errors['responsable'])): ?><p id="responsable-error" class="field-error"><?= htmlspecialchars($errors['responsable'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="email">Email <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="email" type="email" name="email" value="<?= htmlspecialchars((string) ($values['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required maxlength="255" autocomplete="email" aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>" <?= isset($errors['email']) ? 'aria-describedby="email-error"' : '' ?>>
            <?php if (isset($errors['email'])): ?><p id="email-error" class="field-error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="motif">Motif <span class="required-mark" aria-hidden="true">*</span></label>
            <textarea id="motif" name="motif" rows="3" required minlength="5" maxlength="255" aria-invalid="<?= isset($errors['motif']) ? 'true' : 'false' ?>" <?= isset($errors['motif']) ? 'aria-describedby="motif-error"' : '' ?>><?= htmlspecialchars((string) ($values['motif'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php if (isset($errors['motif'])): ?><p id="motif-error" class="field-error"><?= htmlspecialchars($errors['motif'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="date_debut">Début <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="date_debut" type="datetime-local" name="date_debut" value="<?= htmlspecialchars((string) ($values['date_debut'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required aria-invalid="<?= isset($errors['date_debut']) ? 'true' : 'false' ?>" <?= isset($errors['date_debut']) ? 'aria-describedby="date-debut-error"' : '' ?>>
            <?php if (isset($errors['date_debut'])): ?><p id="date-debut-error" class="field-error"><?= htmlspecialchars($errors['date_debut'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="field-group">
            <label for="date_fin">Fin <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="date_fin" type="datetime-local" name="date_fin" value="<?= htmlspecialchars((string) ($values['date_fin'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required aria-invalid="<?= isset($errors['date_fin']) ? 'true' : 'false' ?>" <?= isset($errors['date_fin']) ? 'aria-describedby="date-fin-error"' : '' ?>>
            <?php if (isset($errors['date_fin'])): ?><p id="date-fin-error" class="field-error"><?= htmlspecialchars($errors['date_fin'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
        </div>
        <div class="form-actions">
            <button class="button" type="submit">Réserver</button>
            <a class="button-link" href="/reservations">Retour aux réservations</a>
        </div>
    </form>
</div>

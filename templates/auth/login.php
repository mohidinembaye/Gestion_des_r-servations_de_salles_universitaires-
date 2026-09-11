<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Connexion', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="form-shell">
    <div class="form-heading">
        <p class="eyebrow">Authentification</p>
        <h1>Connexion</h1>
        <p class="intro">Connectez-vous pour accéder à la gestion des réservations.</p>
    </div>

    <?php if (!empty($error)): ?>
        <p class="field-error" style="margin-bottom: 1rem;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form class="form-stack" method="post" action="/login" novalidate>
        <div class="field-group">
            <label for="email">Email <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="email" type="email" name="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>" required autocomplete="email">
        </div>

        <div class="field-group">
            <label for="password">Mot de passe <span class="required-mark" aria-hidden="true">*</span></label>
            <input id="password" type="password" name="password" required autocomplete="current-password">
        </div>

        <div class="form-actions">
            <button class="button" type="submit">Se connecter</button>
        </div>
    </form>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>CovNiv - Connexion</title>
</head>
<body>
    <div class="auth-container">
        <!-- Visual Side -->
        <div class="auth-visual-side">
            <div style="margin-bottom: var(--spacing-8);">
                <i data-lucide="car-front" style="width: 64px; height: 64px; color: white;"></i>
            </div>
            <h2>Bienvenue sur CovNiv</h2>
            <p>La solution de covoiturage exclusive pour les étudiants. Connectez-vous pour proposer ou trouver un trajet vers votre campus.</p>
        </div>

        <!-- Form Side -->
        <div class="auth-form-side">
            <div style="text-align: center; margin-bottom: var(--spacing-8);">
                <a href="/" style="display: inline-block; margin-bottom: var(--spacing-6); color: var(--primary-600);">
                    <i data-lucide="arrow-left"></i> Retour à l'accueil
                </a>
                <h1 style="font-size: 2rem; margin-bottom: var(--spacing-2);">Connexion</h1>
                <p class="text-muted">Entrez vos identifiants pour accéder à votre espace</p>
            </div>

            <!-- Error Messages Container -->
            <?php if (!empty($errors)): ?>
                <div class="card mb-6" style="background-color: var(--danger-50); border-color: var(--danger-500); padding: var(--spacing-4); color: var(--danger-600);">
                    <?php foreach ($errors as $error): ?>
                        <div style="display: flex; align-items: flex-start; gap: var(--spacing-2); margin-bottom: var(--spacing-1);">
                            <i data-lucide="alert-circle" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0;"></i>
                            <span><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/login">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">

                <!-- Student Card Number -->
                <div class="form-group mb-4">
                    <label class="form-label" for="num_carte">Numéro de carte étudiant</label>
                    <div class="input-group">
                        <div class="input-icon">
                            <i data-lucide="id-card"></i>
                        </div>
                        <input type="text" id="num_carte" name="num_carte" class="form-control"
                               placeholder="Ex: 12345678" 
                               value="<?= htmlspecialchars($formData['num_carte'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                               required>
                    </div>
                </div>
                
                <!-- Password -->
                <div class="form-group mb-6">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-2);">
                        <label class="form-label" for="password" style="margin-bottom: 0;">Mot de passe</label>
                        <a href="#" class="text-sm" style="color: var(--primary-600); font-weight: 500;">Mot de passe oublié ?</a>
                    </div>
                    <div class="input-group" style="margin-bottom: 1rem;">
                        <div class="input-icon">
                            <i data-lucide="lock"></i>
                        </div>
                        <input type="password" id="password" name="password" class="form-control"
                               placeholder="Votre mot de passe" required>
                        <button type="button" onclick="togglePasswordVisibility('password', this)" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted); cursor: pointer; display: flex; align-items: center; z-index: 10;">
                            <i data-lucide="eye"></i>
                        </button>
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="form-check text-sm" style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                        <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe" <?= !empty($formData['rememberMe']) ? 'checked' : '' ?> style="width: auto; margin: 0;">
                        <label class="form-check-label text-muted" for="rememberMe" style="margin: 0;">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>


                <!-- Submit Button -->
                <button type="submit" name="submit" class="btn btn-primary w-full" style="padding: 0.875rem; font-size: 1rem;">
                    Se connecter <i data-lucide="log-in" style="width: 1.25rem; height: 1.25rem; margin-left: 0.5rem;"></i>
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--spacing-8);">
                <p class="text-muted">Pas encore de compte ? <a href="/sign-up" style="font-weight: 600;">S'inscrire</a></p>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();

        function togglePasswordVisibility(fieldId, btn) {
            const field = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (field.type === "password") {
                field.type = "text";
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                field.type = "password";
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>

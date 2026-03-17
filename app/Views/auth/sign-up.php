<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>CovNiv - Inscription</title>
</head>
<body>
    <div class="auth-container">
        <!-- Form Side -->
        <div class="auth-form-side" style="max-width: 600px; padding: var(--spacing-6) var(--spacing-8);">
            <div style="text-align: center; margin-bottom: var(--spacing-6);">
                <a href="/" style="display: inline-block; margin-bottom: var(--spacing-4); color: var(--primary-600);">
                    <i data-lucide="arrow-left"></i> Retour à l'accueil
                </a>
                <h1 style="font-size: 2rem; margin-bottom: var(--spacing-2);">Créer un compte</h1>
                <p class="text-muted">Rejoignez la communauté des covoitureurs 100% étudiants</p>
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

            <form action="/sign-up" method="POST" id="signupForm">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8') ?>">
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4);">
                    <div class="form-group mb-4">
                        <label class="form-label" for="prenom">Prénom</label>
                        <div class="input-group">
                            <div class="input-icon"><i data-lucide="user"></i></div>
                            <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Prénom" value="<?= htmlspecialchars($formData['prenom'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label" for="nom">Nom</label>
                        <div class="input-group">
                            <div class="input-icon"><i data-lucide="user"></i></div>
                            <input type="text" id="nom" name="nom" class="form-control" placeholder="Nom" value="<?= htmlspecialchars($formData['nom'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" for="email">Email Universitaire</label>
                    <div class="input-group">
                        <div class="input-icon"><i data-lucide="mail"></i></div>
                        <input type="email" id="email" name="email" class="form-control" placeholder="exemple@univ-bejaia.dz" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                    </div>
                    <div id="email-error" class="text-sm mt-1" style="color: var(--danger-600);"></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4);">
                    <div class="form-group mb-4">
                        <label class="form-label" for="num_carte">N° Carte Étudiant</label>
                        <div class="input-group">
                            <div class="input-icon"><i data-lucide="id-card"></i></div>
                            <input type="text" id="num_carte" name="num_carte" class="form-control" placeholder="8 chiffres" value="<?= htmlspecialchars($formData['num_carte'] ?? '') ?>" required>
                        </div>
                        <div id="card-number-error" class="text-sm mt-1" style="color: var(--danger-600);"></div>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label class="form-label" for="telephone">Téléphone</label>
                        <div class="input-group">
                            <div class="input-icon"><i data-lucide="phone"></i></div>
                            <input type="tel" id="telephone" name="telephone" class="form-control" placeholder="Ex: 0550000000" value="<?= htmlspecialchars($formData['telephone'] ?? '') ?>" required>
                        </div>
                        <div id="phone-error" class="text-sm mt-1" style="color: var(--danger-600);"></div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" for="sexe">Sexe</label>
                    <select id="sexe" name="sexe" class="form-control" required>
                        <option value="" disabled selected>Sélectionnez votre sexe</option>
                        <option value="homme" <?= (isset($formData['sexe']) && $formData['sexe'] === 'homme') ? 'selected' : '' ?>>Homme</option>
                        <option value="femme" <?= (isset($formData['sexe']) && $formData['sexe'] === 'femme') ? 'selected' : '' ?>>Femme</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4);">
                    <div class="form-group mb-6">
                        <label class="form-label" for="password">Mot de passe</label>
                        <div class="input-group">
                            <div class="input-icon"><i data-lucide="lock"></i></div>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Min. 8 caractères" required>
                            <button type="button" onclick="togglePasswordVisibility('password', this)" style="position: absolute; right: 1rem; background: none; border: none; color: var(--text-muted); cursor: pointer; display: flex; align-items: center;">
                                <i data-lucide="eye"></i>
                            </button>
                        </div>
                        <div id="password-length-error" class="text-sm mt-1" style="color: var(--danger-600);"></div>
                    </div>

                    <div class="form-group mb-6">
                        <label class="form-label" for="repeat_password">Confirmer le mot de passe</label>
                        <div class="input-group">
                            <div class="input-icon"><i data-lucide="lock"></i></div>
                            <input type="password" id="repeat_password" name="repeat_password" class="form-control" placeholder="Répéter le mot de passe" required>
                            <button type="button" onclick="togglePasswordVisibility('repeat_password', this)" style="position: absolute; right: 1rem; background: none; border: none; color: var(--text-muted); cursor: pointer; display: flex; align-items: center;">
                                <i data-lucide="eye"></i>
                            </button>
                        </div>
                        <div id="password-match-error" class="text-sm mt-1" style="color: var(--danger-600);"></div>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-primary w-full" style="padding: 0.875rem; font-size: 1rem;">
                    S'inscrire <i data-lucide="user-plus" style="width: 1.25rem; height: 1.25rem; margin-left: 0.5rem;"></i>
                </button>
            </form>

            <div style="text-align: center; margin-top: var(--spacing-6);">
                <p class="text-muted">Déjà un compte ? <a href="/login" style="font-weight: 600;">Se connecter</a></p>
            </div>
        </div>

        <!-- Visual Side -->
        <div class="auth-visual-side" style="background: linear-gradient(135deg, var(--secondary-600), var(--secondary-500));">
            <div style="margin-bottom: var(--spacing-8);">
                <i data-lucide="users" style="width: 64px; height: 64px; color: white;"></i>
            </div>
            <h2>Rejoignez CovNiv</h2>
            <p>Une communauté fermée et sécurisée, réservée aux étudiants. Partagez plus que des trajets.</p>
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

        // Live Validation
        document.getElementById('num_carte').addEventListener('input', function() {
            const val = this.value;
            const err = document.getElementById('card-number-error');
            if (val) {
                if (!/^\d+$/.test(val)) err.textContent = "Chiffres uniquement.";
                else if (val.length !== 8) err.textContent = "Exactement 8 chiffres requis.";
                else err.textContent = "";
            } else err.textContent = "";
        });

        document.getElementById('email').addEventListener('input', function() {
            const val = this.value;
            const err = document.getElementById('email-error');
            if (val && !val.toLowerCase().endsWith('.univ-bejaia.dz')) err.textContent = "L'email doit se terminer par .univ-bejaia.dz";
            else err.textContent = "";
        });

        document.getElementById('password').addEventListener('input', validatePassword);
        document.getElementById('repeat_password').addEventListener('input', validatePasswordMatch);

        function validatePassword() {
            const val = document.getElementById('password').value;
            const err = document.getElementById('password-length-error');
            if (val && val.length < 8) err.textContent = "Au moins 8 caractères.";
            else err.textContent = "";
            validatePasswordMatch();
        }

        function validatePasswordMatch() {
            const p1 = document.getElementById('password').value;
            const p2 = document.getElementById('repeat_password').value;
            const err = document.getElementById('password-match-error');
            if (p2 && p1 !== p2) err.textContent = "Les mots de passe ne correspondent pas.";
            else err.textContent = "";
        }

        document.getElementById('signupForm').addEventListener('submit', function(e) {
            let hasError = false;
            
            const num = document.getElementById('num_carte').value;
            const errNum = document.getElementById('card-number-error');
            if (!/^\d+$/.test(num)) { errNum.textContent = "Chiffres uniquement."; hasError = true; }
            else if (num.length !== 8) { errNum.textContent = "Exactement 8 chiffres requis."; hasError = true; }
            
            const email = document.getElementById('email').value;
            if (!email.toLowerCase().endsWith('.univ-bejaia.dz')) {
                document.getElementById('email-error').textContent = "L'email doit se terminer par .univ-bejaia.dz";
                hasError = true;
            }
            
            const p1 = document.getElementById('password').value;
            if (p1.length < 8) {
                document.getElementById('password-length-error').textContent = "Au moins 8 caractères.";
                hasError = true;
            }
            
            const p2 = document.getElementById('repeat_password').value;
            if (p1 !== p2) {
                document.getElementById('password-match-error').textContent = "Les mots de passe ne correspondent pas.";
                hasError = true;
            }
            
            const tel = document.getElementById('telephone').value;
            if (!/^\d+$/.test(tel) || tel.length !== 10) {
                document.getElementById('phone-error').textContent = "Exactement 10 chiffres requis.";
                hasError = true;
            }
            
            if (hasError) e.preventDefault();
        });
    </script>
</body>
</html>

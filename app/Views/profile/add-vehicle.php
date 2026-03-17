<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>CovNiv - Ajouter un véhicule</title>
</head>
<body class="bg-base">
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="/principal" class="navbar-brand">
                <i data-lucide="car-front"></i>
                CovNiv
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i data-lucide="menu"></i>
            </button>

            <div class="navbar-nav" id="navbarNav">
                <div class="nav-actions">
                    <button class="btn-icon" id="themeToggle" title="Basculer le thème">
                        <i data-lucide="moon"></i>
                    </button>
                    <a href="/logout" class="btn btn-outline" style="color: var(--danger-600); border-color: var(--danger-50); background-color: var(--danger-50);">
                        <i data-lucide="log-out"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content container py-8">
        <div class="max-w-xl mx-auto">
            
            <!-- Header section -->
            <div class="mb-8 text-center">
                <div style="width: 64px; height: 64px; background-color: var(--primary-50); color: var(--primary-600); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4);">
                    <i data-lucide="car" style="width: 32px; height: 32px;"></i>
                </div>
                <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: var(--spacing-2);">Ajouter un véhicule</h1>
                <p class="text-muted">Enregistrez votre véhicule pour proposer des trajets en covoiturage.</p>
            </div>

            <!-- Form Card -->
            <div class="card p-6 md:p-8">
                <?php if (!empty($errors)): ?>
                    <div class="mb-6 p-4 rounded bg-danger-50 text-danger-700" style="background-color: var(--danger-50); border-left: 4px solid var(--danger-600);">
                        <div class="font-bold mb-2 flex items-center gap-2">
                            <i data-lucide="alert-circle" style="width: 18px; height: 18px;"></i>
                            Veuillez corriger les erreurs suivantes :
                        </div>
                        <ul class="list-disc list-inside text-sm">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/ajouter-vehicule" method="POST" class="flex flex-col gap-5">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\AuthSecurity::generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                    
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label for="marque" class="form-label">Marque</label>
                            <div class="input-group">
                                <div class="input-icon"><i data-lucide="tag"></i></div>
                                <input type="text" id="marque" name="marque" class="form-control" placeholder="ex: Peugeot" value="<?= htmlspecialchars($formData['marque'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="modele" class="form-label">Modèle</label>
                            <div class="input-group">
                                <div class="input-icon"><i data-lucide="car-front"></i></div>
                                <input type="text" id="modele" name="modele" class="form-control" placeholder="ex: 208" value="<?= htmlspecialchars($formData['modele'] ?? '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="form-group">
                            <label for="annee" class="form-label">Année</label>
                            <div class="input-group">
                                <div class="input-icon"><i data-lucide="calendar"></i></div>
                                <input type="number" id="annee" name="annee" class="form-control" min="1900" max="<?= date('Y') + 1 ?>" placeholder="ex: 2020" value="<?= htmlspecialchars($formData['annee'] ?? '') ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="couleur" class="form-label">Couleur</label>
                            <div class="input-group">
                                <div class="input-icon"><i data-lucide="palette"></i></div>
                                <input type="text" id="couleur" name="couleur" class="form-control" placeholder="ex: Noir" value="<?= htmlspecialchars($formData['couleur'] ?? '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="plaque_immatriculation" class="form-label">Plaque d'immatriculation</label>
                        <div class="input-group">
                            <div class="input-icon"><i data-lucide="hash"></i></div>
                            <input type="text" id="plaque_immatriculation" name="plaque_immatriculation" class="form-control" placeholder="ex: 12345 125 16" value="<?= htmlspecialchars($formData['plaque_immatriculation'] ?? '') ?>" required>
                        </div>
                        <p class="text-xs text-muted mt-1">Cette information reste privée et ne sera partagée qu'avec les passagers confirmés.</p>
                    </div>

                    <div class="form-group">
                        <label for="nombre_places" class="form-label">Nombre de places disponibles</label>
                        <div class="input-group">
                            <div class="input-icon"><i data-lucide="users"></i></div>
                            <input type="number" id="nombre_places" name="nombre_places" class="form-control" min="1" max="10" placeholder="ex: 3" value="<?= htmlspecialchars($formData['nombre_places'] ?? '') ?>" required>
                        </div>
                        <p class="text-xs text-muted mt-1">N'incluez pas la place du conducteur.</p>
                    </div>

                    <div class="flex gap-4 mt-4">
                        <a href="/profil" class="btn btn-outline flex-1 text-center">Annuler</a>
                        <button type="submit" name="submit" class="btn btn-primary flex-1">
                            <i data-lucide="save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-8 py-6 border-t border-border">
        <div class="container text-center text-muted text-sm">
            <p>&copy; 2026 CovNiv - La solution simple pour les étudiants.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>

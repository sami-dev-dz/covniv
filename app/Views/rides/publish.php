<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>UniRide - Publier un trajet</title>
</head>
<body>
    <!-- Navbar (Dashboard version) -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="/principal" class="navbar-brand">
                <i data-lucide="car-front"></i>
                UniRide
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
    <main class="main-content container">
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar">
                <nav class="sidebar-nav">
                    <a href="/principal">
                        <i data-lucide="home"></i> Tableau de bord
                    </a>
                    <a href="/profil">
                        <i data-lucide="user"></i> Profil
                    </a>
                    <a href="/messagerie">
                        <i data-lucide="message-square"></i> Messagerie
                    </a>
                    <a href="/demandes-recues">
                        <i data-lucide="bell"></i> Demandes reçues
                    </a>
                    <a href="/historique">
                        <i data-lucide="calendar"></i> Historique
                    </a>
                </nav>
            </aside>

            <!-- Content -->
            <div class="dashboard-content">
                <div class="card" style="max-width: 800px; margin: 0 auto;">
                    <div class="card-header" style="background: linear-gradient(135deg, var(--secondary-600), var(--secondary-700)); color: white; padding: var(--spacing-6);">
                        <div style="display: flex; align-items: center; gap: var(--spacing-3);">
                            <i data-lucide="plus-circle" style="width: 2rem; height: 2rem;"></i>
                            <h2 style="color: white; margin: 0;">Publier un trajet</h2>
                        </div>
                        <p style="color: rgba(255,255,255,0.9); margin-top: var(--spacing-2);">Partagez votre trajet et faites des économies.</p>
                    </div>

                    <div class="card-body" style="padding: var(--spacing-8);">
                        
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

                        <?php if (isset($success_message_voiture)): ?>
                            <div class="card mb-6" style="background-color: var(--secondary-50); border-color: var(--secondary-500); padding: var(--spacing-4); color: var(--secondary-600); display: flex; align-items: center; gap: var(--spacing-3);">
                                <i data-lucide="check-circle" style="flex-shrink: 0;"></i>
                                <span><?= htmlspecialchars($success_message_voiture) ?></span>
                            </div>
                        <?php endif; ?>

                        <form id="trajetForm" action="/publier-trajet" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\AuthSecurity::generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                            
                            <!-- Vehicle Section -->
                            <div class="form-group mb-6">
                                <label class="form-label font-bold" for="voiture">Votre Véhicule</label>
                                <?php if (count($user_voitures) > 0): ?>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="car"></i></div>
                                        <select id="voiture" name="voiture" class="form-control" required style="padding-left: 2.75rem;">
                                            <option value="">-- Sélectionnez votre voiture --</option>
                                            <?php foreach ($user_voitures as $voiture): ?>
                                                <option value="<?= $voiture['voiture_id'] ?>">
                                                   <?= htmlspecialchars($voiture['marque'])?> <?= htmlspecialchars($voiture['modele']) ?> (<?= htmlspecialchars($voiture['plaque_immatriculation']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <a href="/ajouter-vehicule" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary-600); font-size: 0.875rem; margin-top: var(--spacing-2); font-weight: 500;">
                                        <i data-lucide="plus" style="width: 1rem; height: 1rem;"></i> Ajouter une autre voiture
                                    </a>
                                <?php else: ?>
                                    <div class="card" style="background-color: var(--bg-surface-hover); border: 1px dashed var(--border-color); text-align: center; padding: var(--spacing-6);">
                                        <p class="text-muted mb-4">Vous devez ajouter un véhicule avant de pouvoir publier un trajet.</p>
                                        <a href="/ajouter-vehicule" class="btn btn-primary">
                                            <i data-lucide="plus"></i> Ajouter un véhicule
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: var(--spacing-6) 0;">

                            <!-- Itinéraire -->
                            <h3 class="mb-4" style="font-size: 1.125rem;">Itinéraire</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-6); margin-bottom: var(--spacing-4); position: relative;">
                                <!-- Départ -->
                                <div>
                                    <div class="form-group mb-4">
                                        <label class="form-label" for="lieu_depart">Ville de départ</label>
                                        <div class="input-group">
                                            <div class="input-icon" style="color: var(--primary-600);"><i data-lucide="map-pin"></i></div>
                                            <select id="lieu_depart" name="lieu_depart" class="form-control" required style="padding-left: 2.75rem;">
                                                <option value="">Sélectionnez un lieu</option>
                                                <optgroup label="Villes & Communes">
                                                    <option value="Bejaia">Béjaïa</option>
                                                    <option value="Akbou">Akbou</option>
                                                    <option value="El Kseur">El Kseur</option>
                                                    <option value="Amizour">Amizour</option>
                                                    <option value="Tichy">Tichy</option>
                                                </optgroup>
                                                <optgroup label="Campus Universitaires">
                                                    <option value="CampusAboudaou">Campus Aboudaou</option>
                                                    <option value="CampusTargaOuzemour">Campus Targa Ouzemour</option>
                                                    <option value="CampusElKseur">Campus El Kseur</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="adresse_depart">Adresse de départ (Précise)</label>
                                        <input type="text" id="adresse_depart" name="adresse_depart" class="form-control" required placeholder="Ex: Cité des 1000 logs">
                                    </div>
                                </div>

                                <!-- Center arrow icon -->
                                <div style="position: absolute; left: 50%; top: 30%; transform: translate(-50%, -50%); width: 32px; height: 32px; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 10;">
                                    <i data-lucide="arrow-right" style="width: 16px; height: 16px; color: var(--text-muted);"></i>
                                </div>

                                <!-- Arrivée -->
                                <div>
                                    <div class="form-group mb-4">
                                        <label class="form-label" for="lieu_arrivee">Destination</label>
                                        <div class="input-group">
                                            <div class="input-icon" style="color: var(--secondary-600);"><i data-lucide="map-pin"></i></div>
                                            <select id="lieu_arrivee" name="lieu_arrivee" class="form-control" required style="padding-left: 2.75rem;">
                                                <option value="">Sélectionnez un lieu</option>
                                                <optgroup label="Campus Universitaires">
                                                    <option value="CampusAboudaou">Campus Aboudaou</option>
                                                    <option value="CampusTargaOuzemour">Campus Targa Ouzemour</option>
                                                    <option value="CampusElKseur">Campus El Kseur</option>
                                                </optgroup>
                                                <optgroup label="Villes & Communes">
                                                    <option value="Bejaia">Béjaïa</option>
                                                    <option value="Akbou">Akbou</option>
                                                    <option value="El Kseur">El Kseur</option>
                                                    <option value="Amizour">Amizour</option>
                                                    <option value="Tichy">Tichy</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="adresse_arrivee">Adresse d'arrivée (Précise)</label>
                                        <input type="text" id="adresse_arrivee" name="adresse_arrivee" class="form-control" placeholder="Ex: Portail Sud">
                                    </div>
                                </div>
                            </div>

                            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: var(--spacing-6) 0;">

                            <!-- Date & Horaires -->
                            <h3 class="mb-4" style="font-size: 1.125rem;">Date et Horaires</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-6); margin-bottom: var(--spacing-6);">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="date_depart">Date de départ</label>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="calendar"></i></div>
                                        <input type="date" id="date_depart" name="date_depart" class="form-control" required min="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" for="heure_depart">Heure de départ</label>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="clock"></i></div>
                                        <input type="time" id="heure_depart" name="heure_depart" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: var(--spacing-6) 0;">

                            <!-- Places & Prix -->
                            <h3 class="mb-4" style="font-size: 1.125rem;">Détails</h3>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-6); margin-bottom: var(--spacing-6);">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="places_disponibles">Places disponibles</label>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="users"></i></div>
                                        <input type="number" id="places_disponibles" name="places_disponibles" class="form-control" min="1" max="10" value="3" required>
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" for="prix">Prix par place (DA)</label>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="coins"></i></div>
                                        <input type="number" id="prix" name="prix" class="form-control" min="0" step="50" value="100" required>
                                    </div>
                                    <span class="text-sm text-muted mt-1" style="display: block;">Mettez 0 pour un trajet gratuit.</span>
                                </div>
                            </div>

                            <div class="form-group mb-8">
                                <label class="form-label" for="notes">Remarques pour les passagers (Optionnel)</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Ex: Je ne prends pas le péage, ponctualité exigée..."></textarea>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary w-full" style="background-color: var(--secondary-600); padding: 1rem; font-size: 1.125rem;" <?= count($user_voitures) === 0 ? 'disabled' : '' ?>>
                                <i data-lucide="check-circle" style="width: 1.5rem; height: 1.5rem;"></i> Confirmer et Publier
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center text-muted text-sm">
            <p>&copy; 2026 UniRide - La solution simple pour les étudiants.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>

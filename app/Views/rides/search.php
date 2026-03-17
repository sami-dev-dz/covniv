<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>CovNiv - Rechercher un trajet</title>
</head>
<body>
    <!-- Navbar (Dashboard version) -->
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
                    <div class="card-header" style="background: linear-gradient(135deg, var(--primary-600), var(--primary-700)); color: white; padding: var(--spacing-6);">
                        <div style="display: flex; align-items: center; gap: var(--spacing-3);">
                            <i data-lucide="search" style="width: 2rem; height: 2rem;"></i>
                            <h2 style="color: white; margin: 0;">Rechercher un trajet</h2>
                        </div>
                        <p style="color: rgba(255,255,255,0.9); margin-top: var(--spacing-2);">Trouvez un covoiturage qui correspond à vos horaires.</p>
                    </div>

                    <div class="card-body" style="padding: var(--spacing-8);">
                        <form id="formulaireRecherche" action="/trajets-disponibles" method="GET">
                            
                            <!-- Origin & Destination -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-6); margin-bottom: var(--spacing-6); position: relative;">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="lieu_depart">Lieu de départ</label>
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
                                                <option value="Aokas">Aokas</option>
                                                <option value="Souk El Ténine">Souk El Ténine</option>
                                                <option value="Sidi-Aïch">Sidi-Aïch</option>
                                                <option value="Tazmalt">Tazmalt</option>
                                            </optgroup>
                                            <optgroup label="Campus Universitaires">
                                                <option value="CampusAboudaou">Campus Aboudaou</option>
                                                <option value="CampusTargaOuzemour">Campus Targa Ouzemour</option>
                                                <option value="CampusElKseur">Campus El Kseur</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>

                                <!-- Center arrow icon -->
                                <div style="position: absolute; left: 50%; top: 60%; transform: translate(-50%, -50%); width: 32px; height: 32px; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 10;">
                                    <i data-lucide="arrow-right" style="width: 16px; height: 16px; color: var(--text-muted);"></i>
                                </div>

                                <div class="form-group mb-0">
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
                                                <option value="Aokas">Aokas</option>
                                                <option value="Souk El Ténine">Souk El Ténine</option>
                                                <option value="Sidi-Aïch">Sidi-Aïch</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: var(--spacing-6) 0;">

                            <!-- Date & Time -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-6); margin-bottom: var(--spacing-6);">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="date_depart">Date de départ</label>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="calendar"></i></div>
                                        <input type="date" id="date_depart" name="date_depart" class="form-control" required min="<?= date('Y-m-d') ?>">
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" for="heure_depart">Heure de départ (approx.)</label>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="clock"></i></div>
                                        <input type="time" id="heure_depart" name="heure_depart" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Options -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-6); margin-bottom: var(--spacing-8);">
                                <div class="form-group mb-0">
                                    <label class="form-label" for="prix">Prix maximum (DA)</label>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="coins"></i></div>
                                        <input type="number" id="prix" name="prix" class="form-control" min="0" placeholder="Optionnel">
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="form-label" for="places">Places requises</label>
                                    <div class="input-group">
                                        <div class="input-icon"><i data-lucide="users"></i></div>
                                        <input type="number" id="places" name="places" class="form-control" min="1" value="1" required>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" name="valider" class="btn btn-primary w-full" style="padding: 1rem; font-size: 1.125rem;">
                                <i data-lucide="search"></i> Rechercher les trajets disponibles
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
            <p>&copy; 2026 CovNiv - La solution simple pour les étudiants.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>UniRide - Réservation Effectuée</title>
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
    <main class="main-content container d-flex" style="min-height: calc(100vh - 140px); align-items: center; justify-content: center;">
        <div class="card text-center" style="max-width: 500px; padding: var(--spacing-8);">
            <div style="width: 80px; height: 80px; background-color: var(--secondary-50); color: var(--secondary-600); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6);">
                <i data-lucide="check-circle" style="width: 48px; height: 48px;"></i>
            </div>
            
            <h1 class="mb-4" style="font-size: 1.875rem;">Réservation Envoyée !</h1>
            
            <p class="text-muted mb-6">
                Votre demande de réservation a bien été envoyée au conducteur. Vous recevrez une notification dès qu'il l'aura acceptée.
            </p>
            
            <div class="card mb-6 text-left" style="background-color: var(--bg-surface-hover); border: 1px dashed var(--border-color); padding: var(--spacing-4);">
                <h3 class="font-bold mb-3" style="font-size: 1.125rem;">Récapitulatif</h3>
                
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="map-pin" style="color: var(--primary-600); width: 1.25rem; height: 1.25rem;"></i>
                    <span><strong>Trajet :</strong> <?= htmlspecialchars($trajet['lieu_depart']) ?> <i data-lucide="arrow-right" style="display: inline; width: 1rem; height: 1rem; vertical-align: text-bottom; color: var(--text-muted); margin: 0 4px;"></i> <?= htmlspecialchars($trajet['lieu_arrivee']) ?></span>
                </div>
                
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="calendar" style="color: var(--text-muted); width: 1.25rem; height: 1.25rem;"></i>
                    <span><strong>Date :</strong> <?= date('d/m/Y', strtotime($trajet['date_depart'])) ?> à <?= date('H:i', strtotime($trajet['heure_depart'])) ?></span>
                </div>
                
                <div class="flex items-center gap-3 mb-2">
                    <i data-lucide="user" style="color: var(--text-muted); width: 1.25rem; height: 1.25rem;"></i>
                    <span><strong>Conducteur :</strong> <?= htmlspecialchars($trajet['prenom']) ?> <?= htmlspecialchars($trajet['nom']) ?></span>
                </div>
                
                <div class="flex items-center gap-3">
                    <i data-lucide="users" style="color: var(--text-muted); width: 1.25rem; height: 1.25rem;"></i>
                    <span><strong>Places :</strong> <?= htmlspecialchars($places_min) ?></span>
                </div>
            </div>
            
            <div class="flex flex-col gap-3">
                <a href="/historique" class="btn btn-primary w-full" style="padding: 0.875rem;">
                    <i data-lucide="calendar-check"></i> Suivre mes réservations
                </a>
                <a href="/principal" class="btn btn-ghost w-full">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container text-center text-muted text-sm">
            <p>&copy; 2026 UniRide - La solution simple pour les étudiants.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>

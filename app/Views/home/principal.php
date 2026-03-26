<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>UniRide - Tableau de bord</title>
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
                    <a href="/principal" class="active">
                        <i data-lucide="home"></i> Tableau de bord
                    </a>
                    <a href="/profil">
                        <i data-lucide="user"></i> Profil
                    </a>
                    <a href="/messagerie">
                        <i data-lucide="message-square"></i> Messagerie
                        <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                            <span class="badge badge-primary" style="margin-left: auto;"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="/demandes-recues">
                        <i data-lucide="bell"></i> Demandes reçues
                        <?php if (isset($demandesRecues) && count($demandesRecues) > 0): ?>
                            <span class="badge badge-warning" style="margin-left: auto;"><?= count($demandesRecues) ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="/historique">
                        <i data-lucide="calendar"></i> Historique
                    </a>
                </nav>
            </aside>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <?php if (isset($prenom)): ?>
                    <div class="card mb-6" style="background: linear-gradient(135deg, var(--primary-600), var(--primary-700)); color: white; border: none; padding: var(--spacing-6);">
                        <h2 style="color: white; margin-bottom: 0;">Bonjour, <?= htmlspecialchars($prenom); ?> 👋</h2>
                        <p style="color: rgba(255,255,255,0.9); margin-top: var(--spacing-2);">Bienvenue sur votre espace UniRide. Que souhaitez-vous faire aujourd'hui ?</p>
                    </div>
                <?php endif; ?>

                <?php if (isset($success_message)): ?>
                    <div class="card mb-6" style="background-color: var(--secondary-50); border-color: var(--secondary-500); padding: var(--spacing-4); color: var(--secondary-600); display: flex; align-items: center; gap: var(--spacing-3);">
                        <i data-lucide="check-circle" style="flex-shrink: 0;"></i>
                        <span><?= $success_message; ?></span>
                    </div>
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-6);">
                    <!-- Recherche Card -->
                    <div class="card card-hover">
                        <div class="card-body text-center" style="padding: var(--spacing-8) var(--spacing-6);">
                            <div style="width: 64px; height: 64px; background-color: var(--primary-50); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4); color: var(--primary-600);">
                                <i data-lucide="search" style="width: 32px; height: 32px;"></i>
                            </div>
                            <h3 class="mb-2">Rechercher un trajet</h3>
                            <p class="text-muted mb-6">Trouvez facilement des trajets qui correspondent à vos besoins et à votre emploi du temps.</p>
                            <a href="/recherche-trajet" class="btn btn-primary w-full">
                                <i data-lucide="search"></i> Rechercher
                            </a>
                        </div>
                    </div>

                    <!-- Proposer Card -->
                    <div class="card card-hover">
                        <div class="card-body text-center" style="padding: var(--spacing-8) var(--spacing-6);">
                            <div style="width: 64px; height: 64px; background-color: var(--secondary-50); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4); color: var(--secondary-600);">
                                <i data-lucide="plus-circle" style="width: 32px; height: 32px;"></i>
                            </div>
                            <h3 class="mb-2">Proposer un trajet</h3>
                            <p class="text-muted mb-6">Partagez votre trajet avec la communauté et aidez d'autres étudiants à se déplacer.</p>
                            <a href="/publication-trajet" class="btn btn-primary w-full" style="background-color: var(--secondary-600);">
                                <i data-lucide="car"></i> Proposer
                            </a>
                        </div>
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

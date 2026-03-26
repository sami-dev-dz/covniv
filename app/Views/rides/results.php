<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>UniRide - Résultats de recherche</title>
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
                
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <a href="/recherche-trajet" class="btn btn-outline" style="padding: 0.5rem 1rem; margin-bottom: var(--spacing-4);">
                            <i data-lucide="arrow-left"></i> Retour
                        </a>
                        <h1 style="font-size: 1.875rem;">Résultats de la recherche</h1>
                    </div>
                </div>

                <?php if (isset($info_message)): ?>
                    <div class="card mb-6" style="background-color: var(--secondary-50); border-color: var(--secondary-500); padding: var(--spacing-4); color: var(--secondary-600); display: flex; align-items: center; gap: var(--spacing-3);">
                        <i data-lucide="info" style="flex-shrink: 0;"></i>
                        <span><?= $info_message; ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($available_count > 0): ?>
                    <p class="text-muted mb-6">
                        <i data-lucide="check-circle" style="display: inline; vertical-align: text-bottom; width: 1.25rem; height: 1.25rem; color: var(--secondary-600);"></i> 
                        <strong><?= htmlspecialchars($available_count) ?></strong> trajet<?= $available_count > 1 ? 's' : '' ?> disponible<?= $available_count > 1 ? 's' : '' ?> trouvé<?= $available_count > 1 ? 's' : '' ?> pour votre recherche.
                    </p>
                    
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-4);">
                        <?php foreach ($available_trips as $trajet): 
                            $trajet_id = (int)$trajet['trajet_id'];
                            $conducteur_id = (int)$trajet['conducteur_id'];
                            $is_own_trip = ($conducteur_id === $_SESSION['user_id']);
                        ?>
                            <div class="card card-hover" style="display: flex; flex-direction: column; @media(min-width: 768px){flex-direction: row;}">
                                <!-- Trajet Info -->
                                <div style="flex: 1; padding: var(--spacing-6); border-right: 1px solid var(--border-color);">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="flex-col text-center" style="width: 60px;">
                                            <span class="font-bold" style="font-size: 1.25rem;"><?= date('H:i', strtotime($trajet['heure_depart'])) ?></span>
                                            <span class="text-sm text-muted"><?= date('d/m/Y', strtotime($trajet['date_depart'])) ?></span>
                                        </div>
                                        
                                        <!-- Visual Line -->
                                        <div style="display: flex; flex-direction: column; align-items: center;">
                                            <div style="width: 12px; height: 12px; border-radius: 50%; border: 2px solid var(--primary-600); background: white;"></div>
                                            <div style="width: 2px; height: 30px; background: var(--border-color);"></div>
                                            <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--secondary-600);"></div>
                                        </div>

                                        <div class="flex-col gap-4" style="flex: 1;">
                                            <div class="font-bold" style="font-size: 1.125rem;"><?= htmlspecialchars($trajet['lieu_depart']) ?></div>
                                            <div class="font-bold" style="font-size: 1.125rem;"><?= htmlspecialchars($trajet['lieu_arrivee']) ?></div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-6 mt-6">
                                        <div class="badge badge-primary">
                                            <i data-lucide="users"></i> <?= htmlspecialchars($trajet['places_disponibles']) ?> place<?= $trajet['places_disponibles'] > 1 ? 's' : '' ?> restante<?= $trajet['places_disponibles'] > 1 ? 's' : '' ?>
                                        </div>
                                        <div style="font-weight: 700; font-size: 1.25rem; color: var(--text-heading);">
                                            <?= htmlspecialchars($trajet['prix']) ?> DA
                                        </div>
                                    </div>
                                </div>

                                <!-- Driver Info & Action -->
                                <div style="width: 100%; max-width: 300px; padding: var(--spacing-6); background-color: var(--bg-surface-hover); display: flex; flex-direction: column; justify-content: space-between;">
                                    <?php
                                    $photo_profil = !empty($trajet['photo_profil']) ? '/' . htmlspecialchars($trajet['photo_profil']) : '';
                                    ?>
                                    <div class="flex items-center gap-4 mb-6">
                                        <?php if ($photo_profil && file_exists(BASE_PATH . 'public' . $photo_profil)): ?>
                                            <img src="<?= $photo_profil ?>" alt="Photo de <?= htmlspecialchars($trajet['prenom']) ?>" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center;">
                                                <i data-lucide="user"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="font-bold"><?= htmlspecialchars($trajet['prenom']) ?> <?= htmlspecialchars($trajet['nom']) ?></div>
                                            <div class="text-sm text-muted capitalize"><?= htmlspecialchars($trajet['sexe']) ?></div>
                                        </div>
                                    </div>

                                    <div>
                                        <?php if (!$is_own_trip): ?>
                                            <a href="/reserver?id=<?= urlencode($trajet_id) ?>&places_min=<?= urlencode($places_min) ?>" class="btn btn-primary w-full">
                                                <i data-lucide="calendar-check"></i> Réserver 
                                            </a>
                                        <?php else: ?>
                                            <div class="card" style="background-color: var(--bg-base); border: 1px dashed var(--border-color); text-align: center; padding: var(--spacing-2);">
                                                <span class="text-sm text-muted">C'est votre trajet</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php else: ?>
                    <div class="card text-center" style="padding: var(--spacing-16) var(--spacing-8);">
                        <div style="width: 80px; height: 80px; background-color: var(--bg-surface-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6); color: var(--text-muted);">
                            <i data-lucide="search-x" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h3 class="mb-2" style="font-size: 1.5rem;">Aucun trajet trouvé</h3>
                        <p class="text-muted mb-8" style="max-width: 400px; margin-left: auto; margin-right: auto;">Il semblerait qu'il n'y ait pas de trajets correspondant à vos critères pour le moment.</p>
                        <a href="/recherche-trajet" class="btn btn-primary">
                            <i data-lucide="search"></i> Modifier la recherche
                        </a>
                    </div>
                <?php endif; ?>
                
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

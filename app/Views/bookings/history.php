<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>CovNiv - Historique et Réservations</title>
    <style>
        .tabs {
            display: flex;
            gap: var(--spacing-2);
            border-bottom: 1px solid var(--border-color);
            margin-bottom: var(--spacing-6);
        }
        .tab-btn {
            background: none;
            border: none;
            padding: var(--spacing-3) var(--spacing-6);
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .tab-btn:hover {
            color: var(--primary-600);
        }
        .tab-btn.active {
            color: var(--primary-600);
            border-bottom-color: var(--primary-600);
        }
        .tab-pane {
            display: none;
        }
        .tab-pane.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-en_attente {
            background-color: var(--primary-50);
            color: var(--primary-700);
        }
        .status-confirmee {
            background-color: var(--secondary-50);
            color: var(--secondary-700);
        }
        .status-annulee {
            background-color: var(--danger-50);
            color: var(--danger-700);
        }
        .status-terminee {
            background-color: var(--bg-surface-hover);
            color: var(--text-muted);
        }
    </style>
</head>
<body>
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
                    <a href="/historique" class="active">
                        <i data-lucide="calendar"></i> Historique
                    </a>
                </nav>
            </aside>

            <!-- Content -->
            <div class="dashboard-content">
                <h1 class="mb-6" style="font-size: 1.875rem;">Mon Historique</h1>

                <div class="tabs">
                    <button class="tab-btn active" data-target="reservations">Mes Réservations</button>
                    <button class="tab-btn" data-target="publications">Mes Trajets Publiés</button>
                </div>

                <!-- Tab 1: Réservations -->
                <div id="reservations" class="tab-pane active">
                    <?php if (!empty($trajets_reserves)): ?>
                        <div class="grid" style="grid-template-columns: 1fr; gap: var(--spacing-4);">
                            <?php foreach($trajets_reserves as $res): ?>
                                <div class="card card-hover" style="display: flex; flex-direction: column; @media(min-width: 768px){flex-direction: row;}">
                                    <div style="flex: 1; padding: var(--spacing-5);">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="status-badge status-<?= strtolower($res['statut_reservation']) ?>">
                                                <?php
                                                $status_icon = '';
                                                switch(strtolower($res['statut_reservation'])) {
                                                    case 'en_attente': $status_icon = 'clock'; $label = 'En attente'; break;
                                                    case 'confirmee': $status_icon = 'check-circle'; $label = 'Confirmée'; break;
                                                    case 'annulee': $status_icon = 'x-circle'; $label = 'Annulée'; break;
                                                    default: $status_icon = 'check'; $label = ucfirst($res['statut_reservation']);
                                                }
                                                ?>
                                                <i data-lucide="<?= $status_icon ?>" style="width: 14px; height: 14px;"></i> <?= $label ?>
                                            </div>
                                            <span class="font-bold text-primary-600" style="font-size: 1.125rem;"><?= htmlspecialchars($res['prix']) ?> DA</span>
                                        </div>
                                        
                                        <h3 class="font-bold mb-3" style="font-size: 1.125rem; display: flex; align-items: center; gap: var(--spacing-2);">
                                            <?= htmlspecialchars($res['lieu_depart']) ?> 
                                            <i data-lucide="arrow-right" style="color: var(--text-muted); width: 1.25rem;"></i> 
                                            <?= htmlspecialchars($res['lieu_arrivee']) ?>
                                        </h3>
                                        
                                        <div class="grid" style="grid-template-columns: repeat(2, 1fr); gap: var(--spacing-3); margin-bottom: var(--spacing-4);">
                                            <div class="flex items-center gap-2 text-muted text-sm">
                                                <i data-lucide="calendar" style="width: 16px;"></i>
                                                <span><?= date('d/m/Y', strtotime($res['date_depart'])) ?></span>
                                            </div>
                                            <div class="flex items-center gap-2 text-muted text-sm">
                                                <i data-lucide="clock" style="width: 16px;"></i>
                                                <span><?= date('H:i', strtotime($res['heure_depart'])) ?></span>
                                            </div>
                                            <div class="flex items-center gap-2 text-muted text-sm">
                                                <i data-lucide="users" style="width: 16px;"></i>
                                                <span><?= htmlspecialchars($res['places_reservees']) ?> place(s)</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-muted text-sm">
                                                <i data-lucide="user" style="width: 16px;"></i>
                                                <span>Pilote: <?= htmlspecialchars($res['prenom']) ?> <?= htmlspecialchars($res['nom']) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div style="padding: var(--spacing-5); background-color: var(--bg-surface-hover); border-left: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: center; gap: var(--spacing-3); min-width: 200px;">
                                        <?php if ($res['statut_reservation'] === 'en_attente' || $res['statut_reservation'] === 'confirmee'): ?>
                                            <form action="/annuler-reservation" method="POST" style="margin: 0;">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\AuthSecurity::generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                                                <input type="hidden" name="reservation_id" value="<?= $res['reservation_id'] ?>">
                                                <button type="submit" class="btn btn-outline w-full" style="color: var(--danger-600); border-color: var(--danger-200);" onclick="return confirm('Annuler cette réservation ?')">
                                                    <i data-lucide="x"></i> Annuler
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($res['statut_reservation'] === 'confirmee'): ?>
                                            <form action="/terminer-reservation" method="POST" style="margin: 0;">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\AuthSecurity::generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                                                <input type="hidden" name="reservation_id" value="<?= $res['reservation_id'] ?>">
                                                <button type="submit" class="btn btn-primary w-full" style="background-color: var(--secondary-600); border-color: var(--secondary-600);" onclick="return confirm('Avez-vous terminé ce voyage ?')">
                                                    <i data-lucide="check-square"></i> Trajet terminé
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <?php if ($res['statut_reservation'] !== 'en_attente' && $res['statut_reservation'] !== 'confirmee'): ?>
                                            <div class="text-center text-muted text-sm">
                                                Aucune action disponible
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="card text-center" style="padding: var(--spacing-16) var(--spacing-8);">
                            <div style="width: 80px; height: 80px; background-color: var(--primary-50); color: var(--primary-600); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6);">
                                <i data-lucide="calendar" style="width: 40px; height: 40px;"></i>
                            </div>
                            <h3 class="mb-2" style="font-size: 1.5rem;">Aucune réservation</h3>
                            <p class="text-muted mb-6">Vous n'avez pas encore effectué de réservation ou d'historique de trajet disponible.</p>
                            <a href="/recherche-trajet" class="btn btn-primary">Rechercher un trajet</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab 2: Publications -->
                <div id="publications" class="tab-pane">
                    <?php if (!empty($trajets_publies)): ?>
                        <div class="grid" style="grid-template-columns: 1fr; gap: var(--spacing-4);">
                            <?php foreach($trajets_publies as $trip): ?>
                                <div class="card card-hover" style="padding: var(--spacing-5);">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="status-badge" style="background-color: var(--secondary-50); color: var(--secondary-700);">
                                            <i data-lucide="check-circle" style="width: 14px; height: 14px;"></i> Publié
                                        </div>
                                        <span class="text-sm text-muted font-mono">Ref: #<?= str_pad($trip['trajet_id'], 5, '0', STR_PAD_LEFT) ?></span>
                                    </div>
                                    
                                    <h3 class="font-bold mb-4" style="font-size: 1.25rem; display: flex; align-items: center; gap: var(--spacing-2);">
                                        <?= htmlspecialchars($trip['lieu_depart']) ?> 
                                        <i data-lucide="arrow-right" style="color: var(--primary-600); width: 1.25rem;"></i> 
                                        <?= htmlspecialchars($trip['lieu_arrivee']) ?>
                                    </h3>
                                    
                                    <div style="display: flex; flex-wrap: wrap; gap: var(--spacing-4); background-color: var(--bg-surface-hover); padding: var(--spacing-4); border-radius: var(--radius-md);">
                                        <div class="flex items-center gap-2">
                                            <div style="width: 32px; height: 32px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); border: 1px solid var(--border-color);">
                                                <i data-lucide="calendar" style="width: 16px;"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs text-muted">Date & Heure</div>
                                                <div class="font-medium text-sm"><?= date('d M Y', strtotime($trip['date_depart'])) ?> à <?= date('H:i', strtotime($trip['heure_depart'])) ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            <div style="width: 32px; height: 32px; background-color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); border: 1px solid var(--border-color);">
                                                <i data-lucide="car" style="width: 16px;"></i>
                                            </div>
                                            <div>
                                                <div class="text-xs text-muted">Véhicule</div>
                                                <div class="font-medium text-sm"><?= htmlspecialchars($trip['marque']) ?> <?= htmlspecialchars($trip['modele']) ?></div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-2 ml-auto">
                                            <div style="text-align: right;">
                                                <div class="text-xs text-muted">Prix unitaire</div>
                                                <div class="font-bold text-primary-600"><?= htmlspecialchars($trip['prix']) ?> DA</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 flex justify-end">
                                         <a href="/demandes-recues" class="btn btn-outline btn-sm" style="font-size: 0.875rem;">
                                            <i data-lucide="eye"></i> Voir les demandes liées
                                         </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="card text-center" style="padding: var(--spacing-16) var(--spacing-8);">
                            <div style="width: 80px; height: 80px; background-color: var(--secondary-50); color: var(--secondary-600); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6);">
                                <i data-lucide="car-front" style="width: 40px; height: 40px;"></i>
                            </div>
                            <h3 class="mb-2" style="font-size: 1.5rem;">Aucun trajet publié</h3>
                            <p class="text-muted mb-6">Vous n'avez pas encore proposé de covoiturage.</p>
                            <a href="/publication-trajet" class="btn btn-primary" style="background-color: var(--secondary-600); border-color: var(--secondary-600);">Publier un trajet</a>
                        </div>
                    <?php endif; ?>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Remove active from all
                    tabBtns.forEach(b => b.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));

                    // Add active to current
                    btn.classList.add('active');
                    const targetId = btn.getAttribute('data-target');
                    document.getElementById(targetId).classList.add('active');
                });
            });
        });
    </script>
</body>
</html>

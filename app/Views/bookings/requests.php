<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>CovNiv - Demandes reçues</title>
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
                    <a href="/demandes-recues" class="active">
                        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                            <span><i data-lucide="bell"></i> Demandes reçues</span>
                            <?php if (count($demandes) > 0): ?>
                                <span class="badge badge-primary" style="font-size: 0.75rem; padding: 0.125rem 0.5rem; border-radius: 999px;"><?= count($demandes) ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                    <a href="/historique">
                        <i data-lucide="calendar"></i> Historique
                    </a>
                </nav>
            </aside>

            <!-- Content -->
            <div class="dashboard-content">
                <div class="flex items-center justify-between mb-6">
                    <h1 style="font-size: 1.875rem;">Demandes Reçues</h1>
                    <?php if (count($demandes) > 0): ?>
                        <div class="badge badge-primary">
                            <?= count($demandes) ?> nouvelle<?= count($demandes) > 1 ? 's' : '' ?> demande<?= count($demandes) > 1 ? 's' : '' ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($demandes)): ?>
                    <div class="card text-center" style="padding: var(--spacing-16) var(--spacing-8);">
                        <div style="width: 80px; height: 80px; background-color: var(--bg-surface-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6); color: var(--text-muted);">
                            <i data-lucide="bell-off" style="width: 40px; height: 40px;"></i>
                        </div>
                        <h3 class="mb-2" style="font-size: 1.5rem;">Aucune demande</h3>
                        <p class="text-muted">Vous n'avez aucune demande de réservation en attente pour le moment.</p>
                    </div>
                <?php else: ?>
                    <div class="grid" style="grid-template-columns: 1fr; gap: var(--spacing-6);">
                        <?php foreach ($demandes as $item): ?>
                            <div class="card card-hover" style="display: flex; flex-direction: column; @media(min-width: 768px){flex-direction: row;}">
                                
                                <!-- Trip Info -->
                                <div style="flex: 1; padding: var(--spacing-6); border-right: 1px solid var(--border-color);">
                                    <div class="mb-4">
                                        <div class="badge badge-primary mb-3" style="background-color: var(--primary-50); color: var(--primary-700);">Demande en attente</div>
                                        <h3 class="font-bold" style="font-size: 1.25rem; display: flex; align-items: center; gap: var(--spacing-2);">
                                            <?= htmlspecialchars($item['lieu_depart']) ?> 
                                            <i data-lucide="arrow-right" style="color: var(--text-muted); width: 1.25rem;"></i> 
                                            <?= htmlspecialchars($item['lieu_arrivee']) ?>
                                        </h3>
                                    </div>
                                    
                                    <div class="grid" style="grid-template-columns: repeat(2, 1fr); gap: var(--spacing-4);">
                                        <div class="flex items-center gap-2 text-muted">
                                            <i data-lucide="calendar"></i>
                                            <span><?= date('d/m/Y', strtotime($item['date_depart'])) ?></span>
                                        </div>
                                        <div class="flex items-center gap-2 text-muted">
                                            <i data-lucide="clock"></i>
                                            <span><?= date('H:i', strtotime($item['heure_depart'])) ?></span>
                                        </div>
                                        <div class="flex items-center gap-2 text-muted">
                                            <i data-lucide="users"></i>
                                            <span><?= htmlspecialchars($item['places_reservees']) ?> place(s)</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Passenger Info & Actions -->
                                <div style="width: 100%; max-width: 320px; padding: var(--spacing-6); background-color: var(--bg-surface-hover); display: flex; flex-direction: column; justify-content: space-between;">
                                    
                                    <div class="flex items-center gap-4 mb-6">
                                        <?php if (!empty($item['photo_profil'])): ?>
                                            <img src="/<?= htmlspecialchars($item['photo_profil']) ?>" alt="Photo de <?= htmlspecialchars($item['prenom']) ?>" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center;">
                                                <i data-lucide="user"></i>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div>
                                            <div class="font-bold"><?= htmlspecialchars($item['prenom']) ?> <?= htmlspecialchars($item['nom']) ?></div>
                                            <div class="text-sm text-muted flex items-center gap-1 mt-1">
                                                <i data-lucide="phone" style="width: 14px; height: 14px;"></i> <?= htmlspecialchars($item['telephone']) ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3">
                                        <div class="flex gap-2">
                                            <?php $csrf_token = htmlspecialchars(\App\Services\AuthSecurity::generateCsrfToken(), ENT_QUOTES, 'UTF-8'); ?>
                                            <button onclick="updateReservation(<?= $item['reservation_id'] ?>, 'confirmee', '<?= $csrf_token ?>')" class="btn btn-primary flex-1" style="background-color: var(--secondary-600); border-color: var(--secondary-600);">
                                                <i data-lucide="check"></i> Accepter
                                            </button>
                                            <button onclick="updateReservation(<?= $item['reservation_id'] ?>, 'annulee', '<?= $csrf_token ?>')" class="btn btn-outline flex-1" style="color: var(--danger-600); border-color: var(--danger-200); background-color: var(--bg-surface);">
                                                <i data-lucide="x"></i> Refuser
                                            </button>
                                        </div>
                                        <a href="/messagerie?destinataire_id=<?= $item['passager_id'] ?>&trajet_id=<?= $item['trajet_id'] ?>" class="btn btn-outline w-full text-center">
                                            <i data-lucide="message-square"></i> Contacter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

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
        async function updateReservation(id, action, csrfToken) {
            const formData = new FormData();
            formData.append('reservation_id', id);
            formData.append('action', action);
            formData.append('csrf_token', csrfToken);
            
            try {
                const res = await fetch('/update-demande', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await res.json();
                
                if (result.success) {
                    // Create a simple toast notification
                    const notif = document.createElement('div');
                    notif.style.position = 'fixed';
                    notif.style.bottom = '20px';
                    notif.style.right = '20px';
                    notif.style.background = 'var(--secondary-600)';
                    notif.style.color = 'white';
                    notif.style.padding = '12px 24px';
                    notif.style.borderRadius = 'var(--radius-md)';
                    notif.style.boxShadow = '0 4px 6px -1px rgba(0,0,0,0.1)';
                    notif.style.zIndex = '9999';
                    notif.style.display = 'flex';
                    notif.style.alignItems = 'center';
                    notif.style.gap = '8px';
                    
                    notif.innerHTML = '<i data-lucide="check-circle"></i> Demande ' + (action === 'confirmee' ? 'acceptée' : 'refusée');
                    document.body.appendChild(notif);
                    lucide.createIcons();
                    
                    setTimeout(() => {
                        location.reload();
                    }, 1000); // Reload after showing success
                    
                } else {
                    alert('Erreur: ' + result.message);
                }
            } catch (err) {
                alert('Une erreur est survenue lors du traitement de la demande.');
                console.error(err);
            }
        }
    </script>
</body>
</html>

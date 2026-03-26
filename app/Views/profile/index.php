<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>UniRide - Mon Profil</title>
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
                    <a href="/profil" class="active">
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
                <h1 class="mb-6" style="font-size: 1.875rem;">Mon Profil</h1>

                <div class="card" style="max-width: 800px;">
                    <form id="profile-form" action="/profil" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\AuthSecurity::generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                        
                        <!-- Header with Profile Picture -->
                        <div style="background: linear-gradient(135deg, var(--primary-600), var(--primary-700)); height: 120px; border-radius: var(--radius-lg) var(--radius-lg) 0 0; position: relative; margin-bottom: 80px;">
                            
                            <!-- Profile Picture Container -->
                            <div style="position: absolute; bottom: -60px; left: var(--spacing-8); display: flex; align-items: flex-end; gap: var(--spacing-4);">
                                <div class="profile-picture" style="position: relative; width: 120px; height: 120px; border-radius: 50%; border: 4px solid var(--bg-surface); background-color: var(--bg-surface); cursor: pointer; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);">
                                    <?php if (!empty($profilePicturePath) && file_exists(BASE_PATH . 'public/' . $profilePicturePath)): ?>
                                        <img src="/<?php echo htmlspecialchars($profilePicturePath); ?>" alt="Photo de profil" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    <?php else: ?>
                                        <div style="width: 100%; height: 100%; border-radius: 50%; background-color: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center;">
                                            <i data-lucide="user" style="width: 3rem; height: 3rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Edit Icon -->
                                    <label for="profile-picture-input" style="position: absolute; bottom: 0; right: 0; background-color: var(--primary-600); color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid var(--bg-surface); transition: all 0.2s ease;">
                                        <i data-lucide="camera" style="width: 1rem; height: 1rem;"></i>
                                    </label>
                                    <input type="file" id="profile-picture-input" name="photo_profil" accept="image/jpeg,image/png,image/gif,image/jpg" style="display: none;">
                                </div>

                                <div style="margin-bottom: var(--spacing-2);">
                                    <?php if (!empty($profilePicturePath) && file_exists(BASE_PATH . 'public/' . $profilePicturePath)): ?>
                                        <button type="button" id="delete-photo-btn" class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.875rem; color: var(--danger-600); border-color: var(--danger-200);">
                                            Supprimer
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($uploadError)): ?>
                            <div style="padding: 0 var(--spacing-8) var(--spacing-6);">
                                <div class="card" style="background-color: var(--danger-50); border-color: var(--danger-500); padding: var(--spacing-4); color: var(--danger-600); display: flex; align-items: center; gap: var(--spacing-2);">
                                    <i data-lucide="alert-circle" style="width: 1.25rem; height: 1.25rem; flex-shrink: 0;"></i>
                                    <span><?php echo htmlspecialchars($uploadError); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div style="padding: 0 var(--spacing-8) var(--spacing-8);">
                            <!-- Informations Générales -->
                            <div class="flex items-center justify-between mb-6">
                                <h2 style="font-size: 1.25rem; margin: 0;">Informations Générales</h2>
                                <!-- Disabled button indicating read-only fields for now as per original code context, could be made editable later -->
                                <button type="button" class="btn btn-outline" disabled style="opacity: 0.5;" title="Modification non implémentée">
                                    <i data-lucide="edit-2"></i> Modifier
                                </button>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-6); @media(min-width: 768px){grid-template-columns: 1fr 1fr;}">
                                
                                <div class="form-group mb-0">
                                    <label class="form-label text-muted">Nom</label>
                                    <div style="padding: 0.75rem 1rem; background-color: var(--bg-base); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-weight: 500;">
                                        <?php echo htmlspecialchars($user['nom'] ?? 'Non défini')?>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label text-muted">Prénom</label>
                                    <div style="padding: 0.75rem 1rem; background-color: var(--bg-base); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-weight: 500;">
                                        <?php echo htmlspecialchars($user['prenom'] ?? 'Non défini')?>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label text-muted">Sexe</label>
                                    <div style="padding: 0.75rem 1rem; background-color: var(--bg-base); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-weight: 500; text-transform: capitalize;">
                                        <?php echo htmlspecialchars($user['sexe'] ?? 'Non défini')?>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="form-label text-muted">Numéro de Téléphone</label>
                                    <div style="padding: 0.75rem 1rem; background-color: var(--bg-base); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-weight: 500;">
                                        <?php echo htmlspecialchars($user['telephone'] ?? 'Non défini')?>
                                    </div>
                                </div>

                                <div class="form-group mb-0" style="grid-column: 1 / -1;">
                                    <label class="form-label text-muted">Numéro de Carte Étudiant</label>
                                    <div style="padding: 0.75rem 1rem; background-color: var(--bg-base); border: 1px solid var(--border-color); border-radius: var(--radius-md); font-weight: 500; display: flex; align-items: center; gap: var(--spacing-2);">
                                        <i data-lucide="credit-card" style="width: 1.25rem; height: 1.25rem; color: var(--text-muted);"></i>
                                        <?php echo htmlspecialchars($user['num_carte'] ?? 'Non défini')?>
                                    </div>
                                </div>
                            </div>

                            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: var(--spacing-8) 0;">

                            <!-- Véhicules -->
                            <div class="flex items-center justify-between mb-6">
                                <h2 style="font-size: 1.25rem; margin: 0;">Mes Véhicules</h2>
                                <a href="/ajouter-vehicule" class="btn btn-primary">
                                    <i data-lucide="plus"></i> Ajouter un véhicule
                                </a>
                            </div>

                            <div class="card" style="background-color: var(--bg-base); border: 1px dashed var(--border-color); text-align: center; padding: var(--spacing-6);">
                                <i data-lucide="car-front" style="width: 3rem; height: 3rem; color: var(--text-muted); margin: 0 auto var(--spacing-4);"></i>
                                <p class="text-muted mb-0">Gérez vos véhicules depuis l'onglet dédié ou ajoutez-en un nouveau pour pouvoir publier des trajets.</p>
                            </div>

                        </div>
                        
                        <input type="hidden" id="delete-photo-input" name="delete_photo" value="false">
                    </form>
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
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const profilePictureInput = document.getElementById('profile-picture-input');
        const profileForm = document.getElementById('profile-form');
        const deletePhotoBtn = document.getElementById('delete-photo-btn');
        const deletePhotoInput = document.getElementById('delete-photo-input');

        function createNotification(message, type = 'success') {
            const notif = document.createElement('div');
            notif.style.position = 'fixed';
            notif.style.top = '20px';
            notif.style.right = '20px';
            notif.style.padding = '12px 24px';
            notif.style.borderRadius = 'var(--radius-md)';
            notif.style.color = 'white';
            notif.style.fontWeight = '500';
            notif.style.zIndex = '9999';
            notif.style.boxShadow = '0 10px 15px -3px rgb(0 0 0 / 0.1)';
            notif.style.transition = 'all 0.3s ease';
            notif.style.transform = 'translateY(-20px)';
            notif.style.opacity = '0';
            
            if (type === 'success') {
                notif.style.backgroundColor = 'var(--secondary-600)';
            } else {
                notif.style.backgroundColor = 'var(--danger-600)';
            }
            
            notif.textContent = message;
            document.body.appendChild(notif);

            setTimeout(() => {
                notif.style.transform = 'translateY(0)';
                notif.style.opacity = '1';
            }, 10);

            setTimeout(() => {
                notif.style.transform = 'translateY(-20px)';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        }

        if (profilePictureInput) {
            profilePictureInput.addEventListener('change', function () {
                const file = this.files?.[0];
                if (!file) return;

                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    createNotification('Format d\'image non supporté. JPG, PNG ou GIF.', 'error');
                    this.value = '';
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    createNotification('Image trop volumineuse. Max 5MB.', 'error');
                    this.value = '';
                    return;
                }

                // Show loading state implicitly by submitting
                const label = document.querySelector('label[for="profile-picture-input"]');
                if (label) {
                    label.innerHTML = '<i data-lucide="loader" class="fa-spin"></i>';
                    lucide.createIcons();
                }
                
                profileForm.submit();
            });
        }

        if (deletePhotoBtn && deletePhotoInput) {
            deletePhotoBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (confirm('Voulez-vous vraiment supprimer votre photo de profil ?')) {
                    deletePhotoInput.value = 'true';
                    profileForm.submit();
                }
            });
        }
    });
    </script>
</body>
</html>

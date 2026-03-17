<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>CovNiv - À propos</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="/" class="navbar-brand">
                <i data-lucide="car-front"></i>
                CovNiv
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i data-lucide="menu"></i>
            </button>

            <div class="navbar-nav" id="navbarNav">
                <a href="/#fonctionnalites" class="nav-link">Fonctionnalités</a>
                <a href="/about" class="nav-link active" style="color: var(--primary-600);">À propos</a>
                <div class="nav-actions">
                    <button class="btn-icon" id="themeToggle" title="Basculer le thème">
                        <i data-lucide="moon"></i>
                    </button>
                    <!-- Affichage conditionnel basé sur la session (simulé ici pour le design) -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/principal" class="btn btn-primary">Mon espace</a>
                    <?php else: ?>
                        <a href="/login" class="btn btn-outline">Connexion</a>
                        <a href="/sign-up" class="btn btn-primary">S'inscrire</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <main class="main-content">
        <!-- Hero Section -->
        <section style="padding: var(--spacing-16) 0; background: linear-gradient(135deg, var(--primary-600), var(--primary-700)); color: white; text-align: center;">
            <div class="container">
                <h1 style="color: white; font-size: 3rem; margin-bottom: var(--spacing-4);">À propos de CovNiv</h1>
                <p style="font-size: 1.25rem; color: rgba(255,255,255,0.9); max-width: 700px; margin: 0 auto;">Une solution de mobilité économique et écologique pensée par et pour la communauté universitaire.</p>
            </div>
        </section>

        <div class="container py-12" style="padding-top: var(--spacing-12); padding-bottom: var(--spacing-12);">
            
            <!-- Mission Section -->
            <section class="mb-16" style="margin-bottom: var(--spacing-16);">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-8); align-items: center;">
                    <div>
                        <h2 class="mb-4" style="font-size: 2.25rem; color: var(--primary-600);">Notre Mission</h2>
                        <p class="text-muted mb-4" style="font-size: 1.125rem;">
                            CovNiv a été créé avec une vision claire : faciliter les déplacements des étudiants tout en réduisant l'empreinte écologique liée aux transports.
                        </p>
                        <p class="text-muted mb-4" style="font-size: 1.125rem;">
                            Notre plateforme met en relation exclusivement les étudiants qui souhaitent partager leurs trajets quotidiens ou occasionnels vers leurs campus universitaires, offrant un cadre sécurisé (accès via email universitaire).
                        </p>
                        <p class="text-muted" style="font-size: 1.125rem;">
                            Face aux enjeux climatiques et à l'augmentation des coûts de transport, nous proposons une alternative économique, écologique et conviviale qui renforce également les liens entre étudiants.
                        </p>
                    </div>
                    <div class="card" style="padding: var(--spacing-8); text-align: center; background-color: var(--bg-surface-hover); border: none;">
                        <i data-lucide="target" style="width: 80px; height: 80px; color: var(--primary-500); margin-bottom: var(--spacing-6);"></i>
                        <h3 class="mb-2">Objectif 2026</h3>
                        <p class="text-muted">Réduire de 30% le nombre de véhicules individuels se rendant sur le campus de l'Université de Béjaïa.</p>
                    </div>
                </div>
            </section>

            <!-- Advantages Section -->
            <section class="mb-16" style="margin-bottom: var(--spacing-16);">
                <div class="text-center mb-12" style="margin-bottom: var(--spacing-12);">
                    <h2 class="mb-2" style="font-size: 2.25rem;">Nos Valeurs Fondamentales</h2>
                    <p class="text-muted">Ce qui nous motive chaque jour à améliorer CovNiv.</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-6);">
                    <div class="card" style="padding: var(--spacing-6); border-top: 4px solid var(--secondary-500);">
                        <i data-lucide="leaf" style="width: 32px; height: 32px; color: var(--secondary-500); margin-bottom: var(--spacing-4);"></i>
                        <h3 class="mb-2">Écologique</h3>
                        <p class="text-muted text-sm">Réduisez votre empreinte carbone en partageant vos trajets et contribuez à un campus plus vert.</p>
                    </div>
                    
                    <div class="card" style="padding: var(--spacing-6); border-top: 4px solid var(--warning-500);">
                        <i data-lucide="wallet" style="width: 32px; height: 32px; color: var(--warning-500); margin-bottom: var(--spacing-4);"></i>
                        <h3 class="mb-2">Économique</h3>
                        <p class="text-muted text-sm">Partagez les frais de carburant et de péage pour économiser sur vos déplacements quotidiens.</p>
                    </div>

                    <div class="card" style="padding: var(--spacing-6); border-top: 4px solid var(--danger-500);">
                        <i data-lucide="shield-check" style="width: 32px; height: 32px; color: var(--danger-500); margin-bottom: var(--spacing-4);"></i>
                        <h3 class="mb-2">Sécurisé</h3>
                        <p class="text-muted text-sm">Communauté fermée et vérifiée (domaine .univ-bejaia.dz), réservée aux membres de l'université.</p>
                    </div>

                    <div class="card" style="padding: var(--spacing-6); border-top: 4px solid var(--primary-500);">
                        <i data-lucide="users" style="width: 32px; height: 32px; color: var(--primary-500); margin-bottom: var(--spacing-4);"></i>
                        <h3 class="mb-2">Communautaire</h3>
                        <p class="text-muted text-sm">Rencontrez d'autres membres de votre communauté universitaire et créez des liens durables.</p>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <section>
                <div class="text-center mb-12" style="margin-bottom: var(--spacing-12);">
                    <h2 class="mb-2" style="font-size: 2.25rem;">L'Équipe derrière CovNiv</h2>
                    <p class="text-muted">Des étudiants passionnés par le code et l'écologie.</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-8);">
                    <!-- Member 1 -->
                    <div class="card text-center card-hover" style="padding: var(--spacing-8) var(--spacing-6); background-color: transparent;">
                        <div style="width: 100px; height: 100px; border-radius: 50%; background-color: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4); font-size: 2.5rem; font-weight: 700;">
                            HY
                        </div>
                        <h3 class="mb-1" style="font-size: 1.25rem;">Hama Yanis</h3>
                        <p class="text-muted text-sm mb-4" style="color: var(--primary-600); font-weight: 500;">Développeur Front-end</p>
                        <div class="flex justify-center gap-2">
                            <a href="#" class="btn-icon" style="color: var(--text-muted);"><i data-lucide="github" style="width: 1.25rem; height: 1.25rem;"></i></a>
                            <a href="#" class="btn-icon" style="color: var(--text-muted);"><i data-lucide="linkedin" style="width: 1.25rem; height: 1.25rem;"></i></a>
                        </div>
                    </div>

                    <!-- Member 2 -->
                    <div class="card text-center card-hover" style="padding: var(--spacing-8) var(--spacing-6); background-color: transparent;">
                        <div style="width: 100px; height: 100px; border-radius: 50%; background-color: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4); font-size: 2.5rem; font-weight: 700;">
                            GS
                        </div>
                        <h3 class="mb-1" style="font-size: 1.25rem;">Ghoul Sami</h3>
                        <p class="text-muted text-sm mb-4" style="color: var(--primary-600); font-weight: 500;">Développeur Front-end / UIUX</p>
                        <div class="flex justify-center gap-2">
                            <a href="#" class="btn-icon" style="color: var(--text-muted);"><i data-lucide="github" style="width: 1.25rem; height: 1.25rem;"></i></a>
                            <a href="#" class="btn-icon" style="color: var(--text-muted);"><i data-lucide="linkedin" style="width: 1.25rem; height: 1.25rem;"></i></a>
                        </div>
                    </div>

                    <!-- Member 3 -->
                    <div class="card text-center card-hover" style="padding: var(--spacing-8) var(--spacing-6); background-color: transparent;">
                        <div style="width: 100px; height: 100px; border-radius: 50%; background-color: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-4); font-size: 2.5rem; font-weight: 700;">
                            MA
                        </div>
                        <h3 class="mb-1" style="font-size: 1.25rem;">Mansour Abderezak</h3>
                        <p class="text-muted text-sm mb-4" style="color: var(--primary-600); font-weight: 500;">Développeur Back-end</p>
                        <div class="flex justify-center gap-2">
                            <a href="#" class="btn-icon" style="color: var(--text-muted);"><i data-lucide="github" style="width: 1.25rem; height: 1.25rem;"></i></a>
                            <a href="#" class="btn-icon" style="color: var(--text-muted);"><i data-lucide="linkedin" style="width: 1.25rem; height: 1.25rem;"></i></a>
                        </div>
                    </div>
                </div>
            </section>

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

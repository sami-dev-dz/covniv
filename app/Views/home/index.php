<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>UniRide - Covoiturage Étudiant</title>
    <style>
        @media (max-width: 768px) {
            .hero .container {
                grid-template-columns: 1fr !important;
                text-align: center;
                gap: var(--spacing-8) !important;
            }
            .hero div[style*="text-align: left"] {
                text-align: center !important;
            }
            .hero h1 {
                font-size: 2.5rem !important;
            }
            .hero .flex {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="/" class="navbar-brand">
                <i data-lucide="car-front"></i>
                UniRide
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i data-lucide="menu"></i>
            </button>

            <div class="navbar-nav" id="navbarNav">
                <a href="#fonctionnalites" class="nav-link">Fonctionnalités</a>
                <a href="/about" class="nav-link">À propos</a>
                <div class="nav-actions">
                    <button class="btn-icon" id="themeToggle" title="Basculer le thème">
                        <i data-lucide="moon"></i>
                    </button>
                    <a href="/login" class="btn btn-outline">Connexion</a>
                    <a href="/sign-up" class="btn btn-primary">S'inscrire</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero" style="position: relative; min-height: 650px; display: flex; align-items: center; padding: var(--spacing-16) 0; background-image: url('/assets/images/image.jpeg'); background-size: cover; background-position: center; background-attachment: fixed; overflow: hidden;">
            <!-- Dark Overlay for Readability -->
            <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(15, 23, 42, 0.45) 100%); z-index: 1;"></div>
            
            <div class="container" style="position: relative; z-index: 2;">
                <div style="max-width: 800px; text-align: left;">
                    <span class="badge badge-primary mb-4" style="background-color: var(--primary-600); color: white; border: none; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);">Nouveau : Rejoignez la communauté UniRide</span>
                    <h1 style="font-size: 4rem; margin-bottom: var(--spacing-6); letter-spacing: -0.02em; line-height: 1.1; color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Le covoiturage repensé pour les <span style="color: var(--primary-400);">étudiants</span></h1>
                    <p style="font-size: 1.35rem; color: rgba(255, 255, 255, 0.9); margin-bottom: var(--spacing-10); max-width: 650px; line-height: 1.6; text-shadow: 0 1px 4px rgba(0,0,0,0.2);">Partagez vos trajets, faites des économies et rencontrez d'autres étudiants de votre campus en toute simplicité et sécurité.</p>
                    <div class="flex gap-4">
                        <a href="/sign-up" class="btn btn-primary" style="font-size: 1.125rem; padding: 0.875rem 2rem; background-color: var(--primary-500); border: none; box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.5);">Commencer maintenant</a>
                        <a href="#fonctionnalites" class="btn btn-outline" style="font-size: 1.125rem; padding: 0.875rem 2rem; color: white; border-color: rgba(255,255,255,0.4); background-color: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">Découvrir</a>
                    </div>
                </div>
            </div>
            
            <!-- Animated decorative element -->
            <div style="position: absolute; bottom: -50px; right: -50px; width: 300px; height: 300px; background: var(--primary-500); filter: blur(100px); opacity: 0.2; border-radius: 50%; z-index: 1;"></div>
        </section>

        <!-- Features Section -->
        <section id="fonctionnalites" style="padding: var(--spacing-16) 0; background-color: var(--bg-surface);">
            <div class="container">
                <div class="text-center mb-12" style="margin-bottom: var(--spacing-12);">
                    <h2 class="mb-4" style="font-size: 2.25rem;">Pourquoi choisir UniRide ?</h2>
                    <p class="text-muted" style="max-width: 600px; margin: 0 auto;">Une plateforme conçue spécifiquement pour répondre aux besoins de mobilité des étudiants universitaires.</p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-8);">
                    <!-- Feature 1 -->
                    <div class="card card-hover" style="text-align: center; border: none; background: transparent; padding: var(--spacing-6);">
                        <div style="width: 64px; height: 64px; background-color: var(--primary-50); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6); color: var(--primary-600);">
                            <i data-lucide="map-pin" style="width: 32px; height: 32px;"></i>
                        </div>
                        <h3 class="mb-2">Trajets Simplifiés</h3>
                        <p class="text-muted">Trouvez rapidement des trajets réguliers vers votre campus.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="card card-hover" style="text-align: center; border: none; background: transparent; padding: var(--spacing-6);">
                        <div style="width: 64px; height: 64px; background-color: var(--secondary-50); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6); color: var(--secondary-600);">
                            <i data-lucide="users" style="width: 32px; height: 32px;"></i>
                        </div>
                        <h3 class="mb-2">Communauté</h3>
                        <p class="text-muted">Voyagez avec des étudiants vérifiés de votre université.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="card card-hover" style="text-align: center; border: none; background: transparent; padding: var(--spacing-6);">
                        <div style="width: 64px; height: 64px; background-color: var(--warning-50); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6); color: var(--warning-500);">
                            <i data-lucide="wallet" style="width: 32px; height: 32px;"></i>
                        </div>
                        <h3 class="mb-2">Économies</h3>
                        <p class="text-muted">Partagez les frais d'essence et réduisez votre budget transport.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="card card-hover" style="text-align: center; border: none; background: transparent; padding: var(--spacing-6);">
                        <div style="width: 64px; height: 64px; background-color: var(--primary-50); border-radius: var(--radius-xl); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--spacing-6); color: var(--primary-600);">
                            <i data-lucide="leaf" style="width: 32px; height: 32px;"></i>
                        </div>
                        <h3 class="mb-2">Écologique</h3>
                        <p class="text-muted">Moins de voitures sur la route pour un campus plus vert.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section style="padding: var(--spacing-16) 0; text-align: center;">
            <div class="container">
                <div class="card" style="padding: var(--spacing-12); background: linear-gradient(135deg, var(--primary-600), var(--primary-700)); color: white; border: none;">
                    <h2 style="color: white; margin-bottom: var(--spacing-4); font-size: 2.25rem;">Prêt à prendre la route ?</h2>
                    <p style="color: rgba(255,255,255,0.9); margin-bottom: var(--spacing-8); max-width: 600px; margin-left: auto; margin-right: auto; font-size: 1.125rem;">Rejoignez des centaines d'étudiants qui utilisent déjà UniRide pour leurs déplacements quotidiens.</p>
                    <a href="/sign-up" class="btn" style="background-color: white; color: var(--primary-700); font-weight: 600; padding: 0.75rem 2rem; font-size: 1.125rem;">Créer un compte gratuitement</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <a href="/" class="navbar-brand" style="margin-bottom: var(--spacing-4); display: inline-flex;">
                        <i data-lucide="car-front"></i>
                        UniRide
                    </a>
                    <p class="text-muted" style="margin-top: var(--spacing-4);">La solution simple, économique et écologique pour les étudiants.</p>
                </div>
                <div class="footer-col">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="#fonctionnalites">Fonctionnalités</a></li>
                        <li><a href="/about">À propos</a></li>
                        <li><a href="/login">Connexion</a></li>
                        <li><a href="/sign-up">Inscription</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Légal</h4>
                    <ul>
                        <li><a href="#">Conditions d'utilisation</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="mailto:contact@uniride.dz" style="display: flex; align-items: center; gap: 0.5rem;"><i data-lucide="mail" style="width: 1rem; height: 1rem;"></i> contact@uniride.dz</a></li>
                    </ul>
                    <div class="social-links mt-4">
                        <a href="#"><i data-lucide="instagram"></i></a>
                        <a href="#"><i data-lucide="twitter"></i></a>
                        <a href="#"><i data-lucide="facebook"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 UniRide - Tous droits réservés.</p>
                <div class="flex items-center gap-2">
                    <span class="text-sm">Fait avec</span>
                    <i data-lucide="heart" style="width: 1rem; height: 1rem; color: var(--danger-500); fill: var(--danger-500);"></i>
                    <span class="text-sm">pour les étudiants</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/assets/js/main.js"></script>
</body>
</html>

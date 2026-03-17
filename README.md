# 🚗 CovNiv

> Plateforme de covoiturage pour les étudiants universitaires.

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green?style=flat)

---

## Fonctionnalités

- 🔍 Recherche et publication de trajets
- 📅 Réservation en ligne avec suivi de statut
- 💬 Messagerie entre conducteurs et passagers
- 👤 Profils utilisateurs et gestion de véhicules
- 🔒 Authentification, CSRF, middleware API sécurisé
- 🌙 Mode sombre / clair
- 📱 Interface responsive
- 🔌 API REST avec auth Bearer token

## Stack

**Backend :** PHP 8.1+ (MVC custom) · MySQL · Apache  
**Frontend :** HTML5 · CSS3 · JavaScript · [Lucide Icons](https://lucide.dev)

## Installation

```bash
# 1. Cloner le projet
git clone https://github.com/your-username/covniv.git
cd covniv

# 2. Configurer l'environnement
cp .env.example .env
# → Éditer .env avec vos identifiants DB

# 3. Créer la base de données
mysql -u root -p -e "CREATE DATABASE covnivii CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4. Importer le schéma
mysql -u root -p covnivii < database/schema.sql

# 5. Pointer votre virtual host vers le dossier public/
```

## Variables d'environnement

```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=covnivii
DB_USERNAME=root
DB_PASSWORD=
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost
API_KEY=your-secure-key
```

## Structure

```
covniv/
├── app/
│   ├── Controllers/     # Contrôleurs (Auth, Ride, Booking, Message, Profile)
│   ├── Models/          # Modèle de données
│   ├── Views/           # Templates (auth, rides, bookings, messaging, profile)
│   ├── Services/        # Logique métier & sécurité
│   └── Helpers/         # Utilitaires
├── config/              # Configuration (DB)
├── database/            # Migrations & schéma SQL
├── public/              # Document root (assets, uploads, index.php)
├── routes/              # Définition des routes
└── logs/                # Journaux
```

## Contribuer

1. Fork le projet
2. Créer une branche (`git checkout -b feature/ma-feature`)
3. Commit (`git commit -m "feat: description"`)
4. Push (`git push origin feature/ma-feature`)
5. Ouvrir une Pull Request

## Licence

MIT — voir [LICENSE](LICENSE) pour les détails.

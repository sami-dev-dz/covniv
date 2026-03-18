<div align="center">
  <img src="public/logo.png" alt="CovNiv Logo" width="120" />
  <h1 align="center">CovNiv</h1>
  <p align="center">
    <strong>A secure, custom MVC-based carpooling platform designed for university students.</strong>
  </p>
  <p align="center">
    <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP" /></a>
    <a href="https://www.mysql.com/"><img src="https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat-square&logo=mysql&logoColor=white" alt="MySQL" /></a>
    <a href="https://httpd.apache.org/"><img src="https://img.shields.io/badge/Apache-Latest-D22128?style=flat-square&logo=apache&logoColor=white" alt="Apache" /></a>
    <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License" /></a>
  </p>
</div>

---

## 🚗 Overview

**CovNiv** is a modern, responsive carpooling web application developed specifically for university students. Built with a custom PHP MVC architecture, it offers a seamless and secure experience for searching, publishing, and managing rides. The platform prioritizes performance and security, featuring robust authentication, CSRF protection, and a professional RESTful API.

## 🚀 Key Features

*   **Ride Management:** Real-time publication and searching of rides with advanced filtering and availability tracking.
*   **Booking System:** Integrated reservation workflow with real-time status updates and automated seat management.
*   **Secure Communication:** Built-in messaging system connecting drivers and passengers for seamless coordination.
*   **Advanced Security:** Enterprise-grade security implementation including CSRF protection, Bearer token authentication, and route-specific middlewares.
*   **RESTful API:** Native API integration for external platform support, optimized for performance and standardization.
*   **Modern UX:** Responsive dark/light mode interface designed with CSS variables and Lucide Icons for a premium feel.

## 🛠️ Tech Stack

*   **Backend Framework:** Custom PHP 8.1+ MVC Architecture
*   **Database:** MySQL (Optimized Schema with Foreign Key Constraints)
*   **Frontend Technologies:** HTML5, Modern CSS3 (Variables, Flexbox, Grid), Vanilla JavaScript
*   **icons:** [Lucide Icons](https://lucide.dev)
*   **API Security:** JWT-inspired Bearer Token Authentication

## 🏁 Quick Start

Follow these instructions to set up the project locally on your machine for development and testing purposes.

### 1. Prerequisites
Ensure you have the following installed:
*   PHP (v8.1 or higher)
*   MySQL (v5.7 or higher)
*   Web Server (Apache/Nginx)

### 2. Installation
Clone the repository and navigate to the project directory:

```bash
git clone https://github.com/sami-dev-dz/covniv.git
cd covniv
```

### 3. Environment Setup
Copy the example environment file and configure your database credentials:
```bash
cp .env.example .env
```
Update `.env` with your local settings:
```env
DB_HOST=localhost
DB_DATABASE=covnivii
DB_USERNAME=root
DB_PASSWORD=your_password
API_KEY=your_secure_key
```

### 4. Database Initialization
Create the database and import the schema:
```bash
mysql -u root -p -e "CREATE DATABASE covnivii CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p covnivii < database/schema.sql
```

### 5. Final Configuration
Point your virtual host's document root to the `public/` directory and ensure URL rewriting is enabled.

## 📁 Folder Structure

The codebase is organized in a clear MVC-based modular structure to ensure maintainability:

```
covniv/
├── app/                 # Core application logic
│   ├── Controllers/     # Route logic (Auth, Ride, Message, etc.)
│   ├── Models/          # Database interaction and data structures
│   ├── Views/           # UI templates and layouts
│   ├── Services/        # Business logic & security middlewares
│   └── Helpers/         # Reusable utility functions
├── config/              # Database and application configuration
├── database/            # SQL schemas and migration scripts
├── public/              # Document root (Entry point, assets, uploads)
├── routes/              # Centralized route definitions
└── logs/                # System and application logs
```

## 🤝 Contributing

Contributions to improve the student commuting experience are always welcome! 

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'feat: Add AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

<div align="center">
  <p>Commute Smarter. Connect Better. 🚗</p>
</div>

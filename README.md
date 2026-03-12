# ⚔️ The Forge - Backend (ECOAL Project)

Welcome to the backend repository of **The Forge**, a web application dedicated to sword collectors and enthusiasts. This project was developed during the **ECOAL International Week**.

---

## 👥 The team

Our team is composed of passionate developers working across the stack:

*   **Lucas Ternel** - Backend Lead & Project Architect 🏗️
*   **Thomas Seyroles** - Frontend Lead & UI Designer 🎨
*   **Thomas Sladojevic** - Backend Developer 💻
*   **Conor McCracken** - Backend Developer 💻
*   **Vitor Silvares** - Frontend Developer 💻

---

## 🚀 Features

- **Robust REST API**: Built with Laravel 11.
- **Sword Management**: Comprehensive database of 58 historic swords with detailed criteria.
- **User Systems**: Authentication via Laravel Sanctum, personal collections, and social features (follow, likes, comments).
- **Automated Data**: Custom seeders to populate the platform with high-quality data and direct image links.

---

## 🛠️ Installation & Setup

Follow these steps to get the backend running on your local machine:

### 1. Prerequisites
Ensure you have **PHP 8.2+** and **Composer** installed.

### 2. Clone and Install
```bash
# Clone the repository
git clone [repository-url]
cd SAE401-back

# Install dependencies
composer install
```

### 3. Environment Configuration
Copy the default environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```
*Note: Make sure to configure your database settings in the `.env` file (DB_DATABASE, DB_USERNAME, etc.).*

### 4. Database Initialization
Run migrations and populate the database with our sword collection:
```bash
php artisan migrate:fresh --seed
```

### 5. Start the Server
```bash
php artisan serve
```
The API will be available at `http://localhost:8000`.

---

## 🌐 Frontend Repository

This backend is designed to work seamlessly with our frontend application. You can find the frontend repository here:

🔗 **[The Forge - Frontend](https://github.com/MMIthomas/SAE401-front)**

---

## 🧪 Tech Stack

- **Framework**: [Laravel 11](https://laravel.com)
- **Authentication**: Laravel Sanctum
- **Database**: MySQL / SQLite
- **Documentation**: Markdown

---

*Developed for the ECOAL International Week 2026.*

# E-commerce API

A production-ready E-commerce API built with Laravel.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Sanctum](https://img.shields.io/badge/Sanctum-Token%20Auth-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-Pest-F5A623?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## ✨ Features

- ✅ JWT authentication
- ✅ Implementing simple CRUD operations.
    - User
    - Product
    - Category
    - Cart $ Cart Item
    - Order
    - Payment

## 🚀 Quick Start

Choose your preferred setup method:

|                  | Local                     | Docker                      |
| ---------------- | ------------------------- | --------------------------- |
| **Requirements** | PHP 8.2+, Composer, MySQL | Docker Desktop              |
| **Setup time**   | ~3 min                    | ~5 min (first build)        |
| **Best for**     | Daily development         | Consistent environments, CI |

---

### 🖥️ Local Setup

#### 1. Clone & install dependencies

```bash
git clone https://github.com/Wowmeww/E-commerce-API.git
cd E-commerce-API
composer install
```

#### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Then open `.env` and fill in your values:

```env
# Application
APP_NAME="E-commerce API"
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000        # Where password reset & verification links point

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_commerce_api
DB_USERNAME=root
DB_PASSWORD=

# Mail — use Mailtrap for local dev: https://mailtrap.io
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="${APP_NAME}"
```

> **`FRONTEND_URL`** controls where password reset and email verification links point.
> Set it to your SPA/frontend origin (e.g. `http://localhost:3000`).
> See [AppServiceProvider](/app/Providers/AppServiceProvider.php) for how these URLs are generated.

#### 3. Create database & run migrations

```bash
# Create the database first (if it doesn't exist)
# mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS e_commerce_api;"

php artisan migrate
```

#### 4. Serve

```bash
php artisan serve
```

✅ API is now available at **`http://localhost:8000/api`**

---

### 🐋 Docker Setup

#### 1. Clone

```bash
git clone https://github.com/Wowmeww/E-commerce-API.git
cd E-commerce-API
```

#### 2. Configure environment

```bash
cp .env.example .env
```

Update the Docker-specific values in `.env`:

```env
# Application
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000

# Database — must match docker-compose.yml service credentials
DB_CONNECTION=mysql
DB_HOST=mysql                             # Docker service name, not 127.0.0.1
DB_PORT=3306
DB_DATABASE=e_commerce_api
DB_USERNAME=laravel
DB_PASSWORD=secret

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="${APP_NAME}"
```

> ⚠️ **`DB_HOST` must be the Docker service name** (`mysql`), not `127.0.0.1`.
> Connecting to `127.0.0.1` inside a container points to the container itself, not the database service.

#### 3. Build & start containers

```bash
docker compose up -d --build
```

This starts three services:

| Service | Description       | Port   |
| ------- | ----------------- | ------ |
| `app`   | PHP 8.3 + Laravel | `8001` |
| `mysql` | MySQL 8.0         | `3306` |

#### 4. Generate key & run migrations

```bash
docker exec e-commerce-api php artisan key:generate
docker exec e-commerce-api php artisan migrate
```

✅ API is now available at **`http://localhost:8001/api`**

<!-- ✅ Mail UI is available at **`http://localhost:8025`** (catches all outgoing email locally) -->

#### Useful Docker commands

```bash
# Fetch and follow the logs of a container:
docker logs -f <container_name>

# Run tests inside container
docker exec <container_name> php artisan test

# Open a shell inside a running container:
docker exec -it <container_name> bash # or sh

# Stop all containers
docker compose down

# Stop and delete volumes (wipes the database)
docker compose down -v
```

---

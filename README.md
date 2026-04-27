# 🔐 Laravel REST API Auth Starter Kit

A production-ready Laravel REST API boilerplate with full authentication — built clean, tested, and ready to extend.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Sanctum](https://img.shields.io/badge/Sanctum-Token%20Auth-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-Pest-F5A623?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## ✨ Features

- ✅ User registration with email verification
- ✅ Token-based login via **Laravel Sanctum**
- ✅ Logout (single-token revocation)
- ✅ Forgot password (email reset link)
- ✅ Reset password (token-based)
- ✅ Authenticated user profile endpoint
- ✅ Consistent JSON response envelope
- ✅ Thin controllers with a dedicated Service layer
- ✅ Form Request validation
- ✅ API Resources for response shaping
- ✅ Global exception handling (401, 422, 404)
- ✅ Feature tests written in **Pest**

---

## 🏗️ Architecture Overview

This project follows SOLID principles and Laravel conventions — without overengineering.

```
Controller  →  FormRequest (validation)
           →  Service (business logic)
           →  ApiResponse + Resource (output)
```

| Layer         | Responsibility                                |
| ------------- | --------------------------------------------- |
| `Controller`  | Accept request, call service, return response |
| `FormRequest` | Validate incoming data                        |
| `Service`     | All business logic (no HTTP concerns)         |
| `Resource`    | Shape and filter model data for output        |
| `ApiResponse` | Consistent JSON envelope across all endpoints |

> **No repository layer** — Eloquent is already an abstraction. Repositories are added only when there's a genuine need (e.g., multiple data sources).

---

## 📁 Folder Structure

```
app/
├── Helpers/
│   └── ApiResponse.php            # Consistent JSON response wrapper
├── Http/
│   ├── Controllers/
│   │   └── Api/
|   |        └── Auth/
│   │           ├── AuthenticatedSessionController.php
|   |           ├── PasswordController.php
|   |           ├── EmailVerificationController.php
|   |           └── RegisterUserController.php
│   ├── Requests/
│   │   └── Auth/
│   │       ├── RegisterRequest.php
│   │       ├── LoginRequest.php
│   │       ├── ForgotPasswordRequest.php
│   │       └── ResetPasswordRequest.php
│   └── Resources/
│       └── UserResource.php
├── Models/
│   └── User.php
└── Services/
    └── Auth/
        ├── AuthenticatedSessionService.php
        ├── PasswordService.php
        ├── EmailVerificationService.php
        └── RegisterUserService.php

routes/
├── api.php
└── auth.php

tests/
└── Feature/Auth/
    └── AuthTest.php               # Pest feature tests
```

---

## ⚙️ Requirements

- PHP **8.2+**
- Composer
- MySQL 8.0+ (or any Laravel-supported database)
- A mail driver (Mailtrap, Mailgun, SMTP, etc.)

---

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
git clone https://github.com/Wowmeww/Laravel-REST-API-Auth-Starter-Kit.git
cd Laravel-REST-API-Auth-Starter-Kit
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
APP_NAME="Laravel Auth API"
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:3000        # Where password reset & verification links point

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_auth
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
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS laravel_auth;"

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
git clone https://github.com/Wowmeww/Laravel-REST-API-Auth-Starter-Kit.git
cd Laravel-REST-API-Auth-Starter-Kit
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
DB_DATABASE=laravel_auth
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
docker exec laravel-api-app php artisan key:generate
docker exec laravel-api-app php artisan migrate
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

## 📡 API Endpoints

All endpoints are prefixed with `/api/auth`.

| Method | Endpoint                           | Auth Required | Description                    |
| ------ | ---------------------------------- | ------------- | ------------------------------ |
| `POST` | `/register`                        | ❌            | Register a new user            |
| `POST` | `/login`                           | ❌            | Login and receive a token      |
| `POST` | `/logout`                          | ✅            | Revoke current token           |
| `GET`  | `/me`                              | ✅            | Get authenticated user profile |
| `POST` | `/forgot-password`                 | ❌            | Send password reset link       |
| `POST` | `/reset-password`                  | ❌            | Reset password via token       |
| `GET`  | `/email/verify/{id}/{hash}`        | Signed URL    | Verify email address           |
| `POST` | `/email/verification-notification` | ✅            | Resend verification email      |

> Protected routes require the header: `Authorization: Bearer {token}`

---

## 📦 Response Format

All responses follow a consistent JSON envelope:

**Success**

```json
{
    "success": true,
    "message": "Login successful.",
    "data": {
        "user": {
            "id": 1,
            "name": "Jane Doe",
            "email": "jane@example.com",
            "email_verified": true,
            "created_at": "2024-01-15T10:30:00.000000Z"
        },
        "token": "2|abc123..."
    }
}
```

**Validation Error (422)**

```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": {
        "email": ["The email has already been taken."]
    }
}
```

**Unauthenticated (401)**

```json
{
    "success": false,
    "message": "Unauthenticated.",
    "errors": null
}
```

---

## 🔑 Authentication Flow

### Registration

```
POST /api/auth/register
→ User created
→ Verification email sent automatically
→ Sanctum token returned immediately
```

### Login

```
POST /api/auth/login
→ Credentials validated
→ Previous tokens revoked (single-session policy)
→ New Sanctum token returned
```

> **Multi-device support:** Remove `$user->tokens()->delete()` from `AuthService::login()` to allow concurrent sessions across devices.

### Password Reset

```
POST /api/auth/forgot-password  { email }
→ Reset link emailed (token stored in password_reset_tokens)

POST /api/auth/reset-password   { token, email, password, password_confirmation }
→ Password updated
→ All tokens revoked (forces re-login on all devices)
```

### Email Verification

```
Registered event fires automatically on registration
→ Laravel sends signed verification URL to user's email
→ User clicks link → GET /api/auth/email/verify/{id}/{hash}
→ Email marked as verified
```

---

## 🧪 Running Tests

Tests are written with **Pest** and use `RefreshDatabase` to reset state between runs.

```bash
# Run all tests
php artisan test

# Run only auth tests
php artisan test --filter=AuthTest

# Run with coverage (requires Xdebug or PCOV)
php artisan test --coverage
```

### Test Coverage

| Test                                         | What it verifies                                        |
| -------------------------------------------- | ------------------------------------------------------- |
| `user can register`                          | 201 response, correct JSON structure, DB record created |
| `register fails with duplicate email`        | 422 response, `success: false`                          |
| `user can login`                             | 200 response, token returned                            |
| `login fails with wrong password`            | 422 response                                            |
| `authenticated user can logout`              | 200 response, token deleted from DB                     |
| `authenticated user can fetch profile`       | Returns correct user email                              |
| `unauthenticated user cannot access profile` | 401 response                                            |
| `forgot password sends reset link`           | Notification dispatched to user                         |
| `user can reset password`                    | 200 response                                            |

---

## 🛡️ Security Defaults

| Practice                           | Implementation                            |
| ---------------------------------- | ----------------------------------------- |
| Password hashing                   | `bcrypt` via Laravel's `hashed` cast      |
| Token revocation on login          | Single-device session enforced            |
| Token revocation on password reset | All devices forced to re-authenticate     |
| Signed email verification URLs     | `middleware(['signed'])` on verify route  |
| Sensitive fields hidden            | `password`, `remember_token` in `$hidden` |
| Verification throttling            | `throttle:6,1` on resend endpoint         |

---

## 🔧 Sanctum Configuration

Token expiry is configured in `config/sanctum.php`:

```php
// Tokens expire after 7 days. Set to null for non-expiring tokens.
'expiration' => 60 * 24 * 7,
```

---

## 🗂️ Postman Collection

Import the following base requests into Postman. Set environment variables:

| Variable   | Value                                      |
| ---------- | ------------------------------------------ |
| `base_url` | `http://localhost:8000`                    |
| `token`    | _(auto-set by login/register test script)_ |

Add this **post-response script** to the Login and Register requests to auto-capture the token:

```javascript
const res = pm.response.json();
if (res.data?.token) {
    pm.environment.set("token", res.data.token);
}
```

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).

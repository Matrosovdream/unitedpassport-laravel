# UnitedPassport

Laravel + Vue.js SPA with Docker.

## Tech Stack

- Laravel 13, PHP 8.4
- Vue 3, PrimeVue (Sakai dashboard), Tailwind CSS 4
- PostgreSQL 16, Redis
- Laravel Sanctum (SPA auth)
- Docker (Nginx, PHP-FPM, queue worker, scheduler)

## Requirements

- Docker & Docker Compose

## Installation

```bash
git clone <repo-url> unitedpassport-laravel
cd unitedpassport-laravel

cp .env.example .env

docker compose -f compose.dev.yaml up -d --build
```

Install dependencies and set up the database:

```bash
docker compose -f compose.dev.yaml exec workspace composer install
docker compose -f compose.dev.yaml exec workspace php artisan key:generate
docker compose -f compose.dev.yaml exec workspace php artisan migrate
docker compose -f compose.dev.yaml exec workspace php artisan db:seed
```

Build frontend assets:

```bash
docker compose -f compose.dev.yaml exec workspace bash -c \
  'export NVM_DIR="$HOME/.nvm" && . "$NVM_DIR/nvm.sh" && npm install && npm run build'
```

## Access

| Service  | URL                    |
|----------|------------------------|
| App      | http://localhost:9090  |
| Adminer  | http://localhost:9091  |

## Default Login

- Email: `admin@admin.com`
- Password: `admin`

## Useful Commands

```bash
# Artisan commands
docker compose -f compose.dev.yaml exec workspace php artisan <command>

# Vite dev server
docker compose -f compose.dev.yaml exec workspace bash -c \
  'export NVM_DIR="$HOME/.nvm" && . "$NVM_DIR/nvm.sh" && npm run dev'

# View logs
docker compose -f compose.dev.yaml logs -f

# Stop
docker compose -f compose.dev.yaml down
```

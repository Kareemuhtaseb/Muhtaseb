# Financial Rental Management System

## Requirements
- Docker & Docker Compose

## Setup
```bash
docker-compose up -d
# run migrations and seeders
docker-compose exec backend php artisan migrate --seed
```
Application URLs:
- Backend API: http://localhost:8000
- Frontend SPA: http://localhost:5173
- Adminer: http://localhost:8080 (server: db, user: postgres, password: postgres)

## API
See [docs/api.md](docs/api.md) for endpoints. Swagger available at `/api/documentation` after generating docs.

## Demo Login
- email: `admin@example.com`
- password: `password`

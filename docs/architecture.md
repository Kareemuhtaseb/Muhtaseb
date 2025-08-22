# Architecture Notes

- **Backend:** Laravel 11 with Sanctum and L5-Swagger. Provides REST API for rental management.
- **Frontend:** Vue 3 SPA using Vite, Composition API and TailwindCSS.
- **Database:** PostgreSQL 15. Schema includes units, tenants, payments, expenses, owners and distributions.
- **Docker:** docker-compose orchestrates services: backend (port 8000), frontend (5173), postgres (5432) and adminer (8080).

## BetX – Modular Sports Betting Platform

This monorepo contains a production-grade sports betting platform inspired by BetPawa/ForteBet, with:

- **Frontend**: Next.js + React, TailwindCSS, Framer Motion (futuristic neon UI)
- **Backend API**: PHP backend in a Laravel-style structure for auth, wallets, betting, and admin APIs
- **Realtime Engine**: Node.js + WebSockets (Socket.io) + Redis for live scores, odds, and bet slip updates
- **Data Stores**: PostgreSQL (or MySQL) + Redis
- **Infra**: Docker, Nginx reverse proxy, cloud-ready deployment configs

### Structure

- `frontend/` – Next.js app (web + admin SPA)
- `backend-laravel/` – PHP backend (Laravel-style modules: auth, wallet, betting, sports, admin)
- `realtime-node/` – Node.js realtime and sports data engine
- `deploy/` – Dockerfiles, docker-compose, and Nginx config
- `docs/` – Architecture, API specs, and database schema

### Getting started (high level)

1. Install **PHP 8.2+**, **Node.js 18+**, **Composer**, **npm**, **Docker**, and **Docker Compose** on your machine.
2. From `backend-laravel/`, run `composer install`, set up `.env`, and run migrations for PostgreSQL/MySQL.
3. From `frontend/`, run `npm install` then `npm run dev`.
4. From `realtime-node/`, run `npm install` then `npm run dev`.
5. Optionally run everything via Docker using `docker-compose` files under `deploy/`.

Each subdirectory includes more detailed README/config files to customize the platform for your environment.


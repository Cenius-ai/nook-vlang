#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

echo "=== Nook — Developer Q&A Forum ==="
echo ""

# ── 0. System deps ────────────────────────────
echo "[0/8] Installing system dependencies (Node.js)..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq
apt-get install -y -qq nodejs npm 2>&1 | tail -1

# ── 1. PHP dependencies ────────────────────────
echo "[1/8] Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --no-security-blocking

# ── 2. Node dependencies ───────────────────────
echo "[2/8] Installing Node dependencies..."
npm install --no-audit --no-fund 2>&1 | tail -1

# ── 3. Frontend build ─────────────────────────
echo "[3/8] Building frontend assets..."
npm run build

# ── 4. Environment ─────────────────────────────
echo "[4/8] Setting up environment..."
if [ ! -f .env ]; then
    cp .env.example .env
fi
php artisan key:generate --force

# ── 5. Storage dirs ────────────────────────────
echo "[5/8] Creating storage directories..."
mkdir -p bootstrap/cache storage/framework/cache/data storage/framework/sessions storage/framework/views storage/app storage/logs
chmod -R 777 bootstrap/cache storage

# ── 6. Database ────────────────────────────────
echo "[6/8] Creating SQLite database..."
mkdir -p database
touch database/database.sqlite

# ── 7. Migrations ──────────────────────────────
echo "[7/8] Running migrations..."
php artisan migrate --force

# ── 8. Seed data ───────────────────────────────
echo "[8/8] Seeding demo data..."
php artisan db:seed --force

# ── Alpine.js ──────────────────────────────────
echo "Downloading Alpine.js..."
mkdir -p public/js
curl -sL https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js -o public/js/alpine.min.js

echo ""
echo "=== Setup complete! ==="
echo ""
echo "Start the server:  php artisan serve --host=0.0.0.0 --port=\${PORT:-8000}"
echo "Demo login:        cenius@cenius.ai / cenius"

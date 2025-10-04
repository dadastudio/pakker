#!/usr/bin/env bash
set -Eeuo pipefail

echo "CLI default php: $(php -v | head -1)"
echo "Forced PHP_BIN: $($PHP_BIN -v | head -1)"

# === KONFIG ===
PHP_BIN="/usr/local/php83/bin/php"
COMPOSER_BIN="$HOME/.local/bin/composer"
APP_DIR="$HOME/domains/beta.pakker.com"     # <- ścieżka do katalogu projektu
BRANCH="main"

cd "$APP_DIR"

# 1) Pobierz kod (bez „merge commitów”)
git fetch origin "$BRANCH"
git diff --quiet || echo "Uwaga: masz lokalne zmiany (niezacommitowane)."
git pull --rebase --autostash origin "$BRANCH"

# 2) Composer (na PHP 8.3)
"$PHP_BIN" "$COMPOSER_BIN" install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 3) Tryb serwisowy (opcjonalnie – włącz gdy robisz migracje)
if [ -f artisan ]; then
  # "$PHP_BIN" artisan down || true

  # Migracje tylko z --force w produkcji
  # "$PHP_BIN" artisan migrate --force

  # Cache/optimize pod PHP 8.3
  "$PHP_BIN" artisan config:clear
  "$PHP_BIN" artisan route:clear
  "$PHP_BIN" artisan view:clear
  # "$PHP_BIN" artisan optimize

  # Jeśli używasz kolejek:
  # "$PHP_BIN" artisan queue:restart || true

  # "$PHP_BIN" artisan up || true
fi

echo "✅ Deploy OK"
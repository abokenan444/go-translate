#!/usr/bin/env bash
set -e

echo "=== Cultural Prompt Engine Installer ==="

BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Detect Laravel project root (current dir)
if [ ! -f "artisan" ]; then
  echo "ERROR: Please run this script from the ROOT of your Laravel project (where artisan is)."
  exit 1
fi

echo "Laravel project detected in: $(pwd)"

# Copy config
echo "- Copying config file..."
mkdir -p config
cp "$BASE_DIR/config/cultural_prompts.php" config/cultural_prompts.php

# Copy app files
echo "- Copying app models and services..."
mkdir -p app/Models/Cultural
cp -r "$BASE_DIR/app/Models/Cultural/." app/Models/Cultural/

mkdir -p app/Services
cp "$BASE_DIR/app/Services/CulturalPromptEngine.php" app/Services/CulturalPromptEngine.php

mkdir -p app/Http/Controllers
cp "$BASE_DIR/app/Http/Controllers/CulturalPromptController.php" app/Http/Controllers/CulturalPromptController.php

# Copy routes
echo "- Merging routes file snippet..."
ROUTES_FILE="routes/web.php"
SNIPPET_FILE="$BASE_DIR/routes/cultural_prompts.php"

if ! grep -q "Cultural Prompt Engine Routes" "$ROUTES_FILE"; then
  echo "" >> "$ROUTES_FILE"
  echo "// Cultural Prompt Engine Routes" >> "$ROUTES_FILE"
  cat "$SNIPPET_FILE" >> "$ROUTES_FILE"
  echo "- Routes appended to routes/web.php"
else
  echo "- Routes already present in routes/web.php, skipping."
fi

# Copy migration & seeder
echo "- Copying migration and seeder..."
mkdir -p database/migrations
cp "$BASE_DIR/database/migrations/2025_01_01_000000_create_cultural_prompt_tables.php" database/migrations/2025_01_01_000000_create_cultural_prompt_tables.php

mkdir -p database/seeders
cp "$BASE_DIR/database/seeders/CulturalPromptsSeeder.php" database/seeders/CulturalPromptsSeeder.php

# Copy seed JSON data
mkdir -p storage/cultural_prompts/bootstrap_data
cp -r "$BASE_DIR/storage/cultural_prompts/bootstrap_data/." storage/cultural_prompts/bootstrap_data/

# Copy admin view
mkdir -p resources/views/admin/cultural_prompts
cp "$BASE_DIR/resources/views/admin/cultural_prompts/index.blade.php" resources/views/admin/cultural_prompts/index.blade.php

echo "- Running migrations..."
php artisan migrate

echo "- Seeding cultural prompts data..."
php artisan db:seed --class="Database\\Seeders\\CulturalPromptsSeeder"

echo "- Clearing caches..."
php artisan optimize:clear

echo "=== Installation complete ==="
echo "You can now open: /admin/cultural-prompts in your browser (after protecting it with auth/middleware)."

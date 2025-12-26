#!/bin/bash

# Setup automated tasks for Hybrid Learning System

echo "🚀 Setting up automated tasks for Hybrid Learning System..."

# Add to crontab
CRON_FILE="/tmp/cultural_translate_cron"

# Create cron jobs
cat > $CRON_FILE << 'EOF'
# Hybrid Learning System - Automated Tasks

# Daily quality monitoring (every day at midnight)
0 0 * * * cd /var/www/cultural-translate-platform && php artisan training:monitor-quality --alert >> /var/log/training-quality.log 2>&1

# Weekly cleanup (every Sunday at 2 AM)
0 2 * * 0 cd /var/www/cultural-translate-platform && php artisan training:manage --cleanup >> /var/log/training-cleanup.log 2>&1

# Weekly export (every Sunday at 3 AM)
0 3 * * 0 cd /var/www/cultural-translate-platform && php artisan training:manage --export >> /var/log/training-export.log 2>&1

# Monthly deduplication (1st of each month at 1 AM)
0 1 1 * * cd /var/www/cultural-translate-platform && php artisan training:manage --deduplicate >> /var/log/training-dedupe.log 2>&1

# Monthly balance check (15th of each month at midnight)
0 0 15 * * cd /var/www/cultural-translate-platform && php artisan training:manage --balance >> /var/log/training-balance.log 2>&1

# Quarterly archive old data (every 3 months on 1st at 4 AM)
0 4 1 */3 * cd /var/www/cultural-translate-platform && php artisan training:manage --archive-old >> /var/log/training-archive.log 2>&1

EOF

# Install cron jobs
crontab -l > /tmp/current_cron 2>/dev/null || true
cat /tmp/current_cron $CRON_FILE | crontab -

echo "✅ Cron jobs installed successfully!"
echo ""
echo "📋 Installed tasks:"
echo "  - Daily quality monitoring (00:00)"
echo "  - Weekly cleanup (Sunday 02:00)"
echo "  - Weekly export (Sunday 03:00)"
echo "  - Monthly deduplication (1st 01:00)"
echo "  - Monthly balance check (15th 00:00)"
echo "  - Quarterly archiving (1st 04:00)"
echo ""
echo "📝 View cron jobs: crontab -l"
echo "📝 Remove cron jobs: crontab -r"
echo ""
echo "🎉 Setup complete!"

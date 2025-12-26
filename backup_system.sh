#!/bin/bash

# Certified Translation System - Automated Backup Script
# This script creates backups of database and certified documents

BACKUP_DIR="/var/www/cultural-translate-platform/storage/backups"
DB_PATH="/var/www/cultural-translate-platform/database/database.sqlite"
DOCS_PATH="/var/www/cultural-translate-platform/storage/app/certified-documents"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"

echo "=== Certified Translation Backup Started at $(date) ==="

# Backup Database
echo "Backing up database..."
if [ -f "$DB_PATH" ]; then
    cp "$DB_PATH" "$BACKUP_DIR/database_$DATE.sqlite"
    gzip "$BACKUP_DIR/database_$DATE.sqlite"
    echo "✓ Database backed up: database_$DATE.sqlite.gz"
else
    echo "✗ Database not found!"
fi

# Backup Certified Documents
echo "Backing up certified documents..."
if [ -d "$DOCS_PATH" ]; then
    tar -czf "$BACKUP_DIR/documents_$DATE.tar.gz" -C "$DOCS_PATH" .
    echo "✓ Documents backed up: documents_$DATE.tar.gz"
else
    echo "✗ Documents directory not found!"
fi

# Calculate backup sizes
DB_SIZE=$(du -h "$BACKUP_DIR/database_$DATE.sqlite.gz" 2>/dev/null | cut -f1)
DOCS_SIZE=$(du -h "$BACKUP_DIR/documents_$DATE.tar.gz" 2>/dev/null | cut -f1)

echo "Backup sizes: Database=$DB_SIZE, Documents=$DOCS_SIZE"

# Remove old backups (older than RETENTION_DAYS)
echo "Cleaning old backups (older than $RETENTION_DAYS days)..."
find "$BACKUP_DIR" -name "database_*.sqlite.gz" -mtime +$RETENTION_DAYS -delete
find "$BACKUP_DIR" -name "documents_*.tar.gz" -mtime +$RETENTION_DAYS -delete
echo "✓ Old backups cleaned"

# List current backups
echo "Current backups:"
ls -lh "$BACKUP_DIR" | grep -E "(database|documents)" | tail -5

# Create backup log
echo "$(date): Backup completed - DB: $DB_SIZE, Docs: $DOCS_SIZE" >> "$BACKUP_DIR/backup.log"

echo "=== Backup Completed at $(date) ==="

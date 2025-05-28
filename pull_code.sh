#!/bin/bash

# ======================================================
# SCRIPT: Automated Deployment for Laravel Project
# AUTHOR: Enhanced by Claude 3.7 Sonnet
# ======================================================

# Enable strict mode
set -euo pipefail  # Exit on error, unbound variables, and pipe failures
trap 'echo "❌ Error occurred at line $LINENO. Previous command exited with status: $?"; exit 1' ERR

# Configuration variables
REPO_URL="https://github.com/binh-dev-k2/cong_no.git"
REMOTE_NAME="binh.dev.02"
BRANCH="live1"
LOG_FILE="deployment_$(date +%Y%m%d_%H%M%S).log"

# Helper functions
log() {
    local message="[$(date '+%Y-%m-%d %H:%M:%S')] $1"
    echo "$message" | tee -a "$LOG_FILE"
}

check_command() {
    if ! command -v "$1" &>/dev/null; then
        log "❌ Required command '$1' not found. Please install it and try again."
        exit 1
    fi
}

run_command() {
    local desc="$1"
    shift
    log "🔄 $desc..."
    if ! "$@" >> "$LOG_FILE" 2>&1; then
        log "❌ Command failed: $*"
        log "Check $LOG_FILE for details."
        exit 1
    fi
    log "✅ Done: $desc"
}

# Check for required commands
check_command git
check_command composer
check_command php

# Move to script directory
cd "$(dirname "$0")" || { log "❌ Failed to change to script directory"; exit 1; }
log "📂 Working directory: $(pwd)"

# Repository setup/update
if [ ! -d ".git" ]; then
    log "🚀 First-time repository clone"
    run_command "Cloning repository" git clone --branch "$BRANCH" "$REPO_URL" .
else
    # Check if we're on the right remote/branch
    if ! git remote | grep -q "$REMOTE_NAME"; then
        log "🔄 Adding remote"
        run_command "Adding git remote" git remote add "$REMOTE_NAME" "$REPO_URL"
    fi

    run_command "Fetching latest changes" git fetch "$REMOTE_NAME"

    # Stash local changes if any exist
    if ! git diff --quiet; then
        log "⚠️ Local changes detected, stashing them"
        run_command "Stashing local changes" git stash
    fi

    run_command "Checking out branch $BRANCH" git checkout "$BRANCH"
    run_command "Resetting to remote branch" git reset --hard "$REMOTE_NAME/$BRANCH"
    run_command "Pulling latest changes" git pull "$REMOTE_NAME" "$BRANCH" --ff-only
fi

# Laravel setup
# Ensure .env file exists
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    log "📄 Creating .env file from example"
    run_command "Creating .env file" cp .env.example .env
    run_command "Generating app key" php artisan key:generate
fi

# Composer dependencies
log "📦 Managing composer dependencies"
if [ -f "composer.lock" ]; then
    run_command "Removing composer lock file" rm composer.lock
fi
run_command "Clearing composer cache" composer clear-cache
run_command "Installing dependencies" composer install --no-interaction --prefer-dist --optimize-autoloader

# Install dbal if not already installed (prevents migration schema errors)
if ! composer show | grep -q "doctrine/dbal"; then
    log "📦 Installing doctrine/dbal for schema support"
    run_command "Installing doctrine/dbal" composer require doctrine/dbal --no-interaction
fi

# Database operations
if [ -f "artisan" ]; then
    # Check if database is configured and accessible
    if php artisan migrate:status &>/dev/null; then
        log "⚙️ Running database migrations"
        if ! php artisan migrate --force; then
            log "⚠️ Migration failed"
        fi

        # Only run seeders if explicitly needed (check if seeders directory has files)
        if [ -d "database/seeders" ] && [ "$(ls -A database/seeders)" ]; then
            log "🌱 Seeding database"
            run_command "Seeding database" php artisan db:seed --force
        else
            log "🌱 No seeders found, skipping seeding"
        fi
    else
        log "⚠️ Could not check migration status. Database might not be configured correctly."
    fi

    # Cache optimization
    log "🗑️ Clearing and optimizing cache"
    run_command "Clearing cache" php artisan optimize:clear
    run_command "Optimizing" php artisan optimize

    # Clear any compiled views
    run_command "Clearing compiled views" php artisan view:clear

    # Update permissions if needed (for Linux/Unix environments)
    if [[ "$OSTYPE" == "linux-gnu"* || "$OSTYPE" == "darwin"* ]]; then
        log "🔒 Setting proper permissions"
        run_command "Setting storage permissions" chmod -R 775 storage bootstrap/cache
        run_command "Setting ownership" chown -R $(whoami):www-data .
    fi
fi

log "✅ Deployment completed successfully!"
log "📝 Deployment log saved to $LOG_FILE"

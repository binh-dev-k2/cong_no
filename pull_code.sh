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

# Set HOME and COMPOSER_HOME environment variables if not set
# This is crucial for Composer to work correctly
if [ -z "${HOME:-}" ]; then
    export HOME="$(pwd)"
    log "⚠️ HOME environment variable was not set. Setting to $(pwd)"
fi

if [ -z "${COMPOSER_HOME:-}" ]; then
    export COMPOSER_HOME="${HOME}/.composer"
    mkdir -p "${COMPOSER_HOME}"
    log "ℹ️ COMPOSER_HOME set to ${COMPOSER_HOME}"
fi

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

# Check for required commands and install if missing
check_command() {
    if ! command -v "$1" &>/dev/null; then
        log "⚠️ Required command '$1' not found. Attempting to install it..."
        case "$1" in
            composer)
                install_composer
                ;;
            git)
                log "❌ Git is required but not found. Please install Git manually and try again."
                exit 1
                ;;
            php)
                log "❌ PHP is required but not found. Please install PHP manually and try again."
                exit 1
                ;;
            *)
                log "❌ Required command '$1' not found and can't be auto-installed."
                exit 1
                ;;
        esac
    fi
}

install_composer() {
    log "📥 Installing Composer..."

    # Ensure temporary directory exists and is writable
    TEMP_DIR="${HOME}/tmp"
    mkdir -p "${TEMP_DIR}"
    cd "${TEMP_DIR}"

    # Download and verify Composer installer
    log "Downloading Composer installer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

    # Skip checksum verification if curl is not available
    if command -v curl &>/dev/null; then
        EXPECTED_CHECKSUM="$(curl -s https://composer.github.io/installer.sig)"
        ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

        if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
            log "❌ Composer installer corrupt"
            rm composer-setup.php
            exit 1
        fi
    else
        log "⚠️ curl not available, skipping Composer installer verification"
    fi

    # Install Composer
    php composer-setup.php --quiet
    INSTALL_RESULT=$?
    rm composer-setup.php

    if [ $INSTALL_RESULT -ne 0 ]; then
        log "❌ Composer installation failed"
        exit 1
    fi

    # Move composer.phar to project directory
    SCRIPT_DIR="$(dirname "$(readlink -f "$0")")"
    mv composer.phar "${SCRIPT_DIR}/"
    cd "${SCRIPT_DIR}"

    chmod +x composer.phar
    log "✅ Installed Composer locally as composer.phar"

    # Create composer function to use local composer.phar
    composer() {
        php "${SCRIPT_DIR}/composer.phar" "$@"
    }
    export -f composer
}

# Get absolute path of script directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
log "📂 Script directory: ${SCRIPT_DIR}"

# Check for required commands
check_command git
check_command php
check_command composer

# Move to script directory
cd "${SCRIPT_DIR}" || { log "❌ Failed to change to script directory"; exit 1; }
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

# Use local composer.phar if it exists and global command failed
if [ -f "${SCRIPT_DIR}/composer.phar" ]; then
    log "🔄 Using local composer.phar"
    COMPOSER_CMD="php ${SCRIPT_DIR}/composer.phar"
else
    COMPOSER_CMD="composer"
fi

run_command "Clearing composer cache" $COMPOSER_CMD clear-cache || log "⚠️ Failed to clear composer cache, continuing anyway"
run_command "Installing dependencies" $COMPOSER_CMD install --no-interaction --prefer-dist --optimize-autoloader

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

    # Create storage link with error handling
    log "🔗 Creating storage link"
    if ! php artisan storage:link; then
        log "⚠️ Failed to create storage link, attempting to create it manually"
        # Try to create the symbolic link manually
        if [ -d "public" ] && [ -d "storage/app/public" ]; then
            if [ -L "public/storage" ]; then
                log "🔄 Removing existing storage link"
                rm -f "public/storage"
            fi
            if ln -sf "../storage/app/public" "public/storage"; then
                log "✅ Created storage link manually"
            else
                log "❌ Failed to create storage link manually"
            fi
        else
            log "❌ Could not create storage link: public directory or storage/app/public not found"
        fi
    else
        log "✅ Storage link created successfully"
    fi

    # Update permissions if needed (for Linux/Unix environments)
    if [[ "$OSTYPE" == "linux-gnu"* || "$OSTYPE" == "darwin"* ]]; then
        log "🔒 Setting proper permissions"

        # Set directory permissions
        if [ -d "storage" ]; then
            run_command "Setting storage permissions" chmod -R 775 storage || log "⚠️ Failed to set storage permissions"
        fi

        if [ -d "bootstrap/cache" ]; then
            run_command "Setting bootstrap cache permissions" chmod -R 775 bootstrap/cache || log "⚠️ Failed to set bootstrap cache permissions"
        fi

        # Try to set ownership, but don't fail if it doesn't work
        CURRENT_USER=$(whoami)

        # Check if www-data group exists
        if getent group www-data >/dev/null 2>&1; then
            log "🔄 Attempting to set ownership to ${CURRENT_USER}:www-data"
            if ! chown -R "${CURRENT_USER}:www-data" . 2>/dev/null; then
                log "⚠️ Could not set ownership to ${CURRENT_USER}:www-data, trying current user only"
                if ! chown -R "${CURRENT_USER}" . 2>/dev/null; then
                    log "⚠️ Could not change ownership, continuing without ownership changes"
                fi
            fi
        else
            log "⚠️ www-data group not found, setting ownership to current user only"
            if ! chown -R "${CURRENT_USER}" . 2>/dev/null; then
                log "⚠️ Could not change ownership to ${CURRENT_USER}, continuing without ownership changes"
            fi
        fi
    fi
fi

log "✅ Deployment completed successfully!"
log "📝 Deployment log saved to $LOG_FILE"

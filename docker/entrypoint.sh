#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Use environment variables passed from docker-compose, with a default fallback
USER_ID=${LOCAL_USER_ID:-1000}
GROUP_ID=${LOCAL_GROUP_ID:-1000}

echo "Starting with UID : $USER_ID, GID: $GROUP_ID"

# Delete user and group if they exist to avoid conflicts.
if id -u www-data >/dev/null 2>&1; then
    deluser www-data
fi
if getent group www-data >/dev/null; then
    delgroup www-data
fi

# Create the group and user with the specified IDs
addgroup --gid $GROUP_ID www-data
adduser --uid $USER_ID --gid $GROUP_ID --disabled-password --gecos "" www-data

# Set ownership for all necessary directories.
# This ensures the new www-data user can write to the app, log, and process ID files.
chown -R www-data:www-data /var/www/app_aguiasdosul /var/log/supervisor /var/run/supervisor

echo "Permissions fixed."

# Execute the main command specified in the docker-compose.yml
exec "$@"

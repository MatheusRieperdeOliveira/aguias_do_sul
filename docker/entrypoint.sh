#!/bin/sh

# Exit immediately if a command exits with a non-zero status.
set -e

# Use environment variables passed from docker-compose, with a default fallback
USER_ID=${LOCAL_USER_ID:-1000}
GROUP_ID=${LOCAL_GROUP_ID:-1000}

echo "Starting with UID : $USER_ID, GID: $GROUP_ID"

# Delete user and group if they exist to avoid conflicts.
if id -u aguias >/dev/null 2>&1; then
    deluser aguias
fi
if getent group aguias >/dev/null; then
    delgroup aguias
fi

# Create the group and user with the specified IDs
addgroup --gid $GROUP_ID aguias
adduser --uid $USER_ID --gid $GROUP_ID --disabled-password --gecos "" aguias

# Set ownership for all necessary directories.
# This ensures the new aguias user can write to the app, log, and process ID files.
chown -R aguias:aguias /var/www/app_aguiasdosul /var/log/supervisor /var/run/supervisor

echo "Permissions fixed."

# Execute the main command specified in the docker-compose.yml
exec "$@"

#!/bin/sh

set -e

USER_ID=${LOCAL_USER_ID:-1000}
GROUP_ID=${LOCAL_GROUP_ID:-1000}

echo "Starting with UID : $USER_ID, GID: $GROUP_ID"

if id -u aguias >/dev/null 2>&1; then
    deluser aguias
fi
if getent group aguias >/dev/null; then
    delgroup aguias
fi

addgroup --gid $GROUP_ID aguias
adduser --uid $USER_ID --gid $GROUP_ID --disabled-password --gecos "" aguias

chown -R aguias:aguias /var/www/app_aguiasdosul /var/log/supervisor /var/run/supervisor

echo "Permissions fixed."

exec "$@"

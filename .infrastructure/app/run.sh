#!/usr/bin/env bash

set -e

[[ -z "$APP_ENV" ]] && echo "APP_ENV environment variable not set" && exit 1;

echo "run.sh: [APP_ENV:${APP_ENV}]"

mkdir -p /app/storage/logs/nginx /app/storage/logs/supervisor /app/storage/logs/php-fpm
chmod -R 777 /app/storage
chmod -R 777 /app/bootstrap/cache

if [[ "$APP_ENV" = "production" ]]
then
    echo "run.sh: running production actions"

    composer install --no-dev --no-interaction

    artisan migrate --force
    artisan cache:clear
    artisan config:cache
    artisan route:trans:cache

    npm install
    npm run production
else
    echo "run.sh: running non-production actions"

    composer install --no-interaction

    artisan migrate
    artisan cache:clear
    # artisan migrate --database=test
    # vnpm install
    # npm run development
fi

rm -rf /app/public/storage
artisan storage:link

supervisord -c /etc/supervisor/supervisord.conf

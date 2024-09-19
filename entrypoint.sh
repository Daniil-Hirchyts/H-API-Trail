#!/bin/sh

composer install --prefer-dist --no-progress --no-interaction

npm install

php bin/console doctrine:database:create --if-not-exists
php bin/console doctrine:migrations:migrate --no-interaction

if [ "$APP_ENV" = "prod" ]; then
    npm run build

    exec apache2-foreground
else
    symfony server:start --no-tls --port=8888 --allow-http
fi

wait

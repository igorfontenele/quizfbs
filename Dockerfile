# syntax=docker/dockerfile:1

# ────────────────────────────────────────────────────────────────────────────
# Stage 1 — Dependências PHP (Composer)
#
# Flux Pro é um pacote pago. Defina a env var COMPOSER_AUTH no Railway com:
#   {"http-basic":{"composer.fluxui.dev":{"username":"SEU_EMAIL","password":"SUA_LICENSE_KEY"}}}
# O Composer lê COMPOSER_AUTH automaticamente; o Railway a injeta no build.
# ────────────────────────────────────────────────────────────────────────────
FROM composer:2 AS vendor
WORKDIR /app
ARG COMPOSER_AUTH
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative --no-dev \
 && php artisan package:discover --ansi || true

# ────────────────────────────────────────────────────────────────────────────
# Stage 2 — Build dos assets (Vite 8 + Tailwind 4 + Flux CSS)
# Precisa do /vendor presente: resources/css/app.css importa vendor/livewire/flux/dist/flux.css
# ────────────────────────────────────────────────────────────────────────────
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# ────────────────────────────────────────────────────────────────────────────
# Stage 3 — Runtime: nginx + php-fpm (imagem pronta p/ Laravel)
# ────────────────────────────────────────────────────────────────────────────
FROM serversideup/php:8.4-fpm-nginx

USER root
RUN install-php-extensions pdo_pgsql pgsql gd
USER www-data

# Recursos da imagem serversideup: rodam no boot do container.
ENV AUTORUN_ENABLED=true \
    PHP_OPCACHE_ENABLE=1 \
    AUTORUN_LARAVEL_MIGRATION=true \
    AUTORUN_LARAVEL_MIGRATION_ISOLATION=true \
    AUTORUN_LARAVEL_STORAGE_LINK=true \
    AUTORUN_LARAVEL_CONFIG_CACHE=true \
    AUTORUN_LARAVEL_ROUTE_CACHE=true \
    AUTORUN_LARAVEL_VIEW_CACHE=true \
    AUTORUN_LARAVEL_EVENT_CACHE=true

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build

RUN chmod -R ug+rwX storage bootstrap/cache

# A imagem expõe 8080 e já traz healthcheck. Railway detecta o EXPOSE.

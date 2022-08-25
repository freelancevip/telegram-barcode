# Описание

Телеграм бот...

## Установка

```bash
git clone
composer install 
cp .env.example .env 
php artisan migrate 
php artisan key:generate 
php artisan storage:link
php artisan queue:work --queue tgMessages
php artisan telebot:polling
```

## Supervisor

```
/etc/supervisor/conf.d/barcode-queue.conf
[program:barcode-queue]
command=/opt/php81/bin/php /var/www/telega/data/www/telega.4natic.ru/artisan queue:work --queue tgMessages --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=telega
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/telega/data/www/telega.4natic.ru/queue.log
stopwaitsecs=3600

/etc/supervisor/conf.d/barcode-telegram-polling.conf
[program:barcode-telegram-polling]
command=/opt/php81/bin/php /var/www/telega/data/www/telega.4natic.ru/artisan telebot:polling
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=telega
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/telega/data/www/telega.4natic.ru/polling.log
stopwaitsecs=3600
```

***restart***

```
supervisorctl restart barcode-telegram-polling
supervisorctl restart barcode-queue
```

## Админка

Направить apache на папку public.

Панель:

  ```
  /dashboard
  ```

Login: admin@admin.com
Password: password

## Development

```bash
php artisan serve
```

```bash
php artisan telebot:polling
```

```bash
php artisan queue:work --queue tgMessages
```

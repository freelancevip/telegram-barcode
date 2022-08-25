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
/etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
command=/path/to/php/php /path/to/app/artisan queue:work --queue default,tgMessages --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=your_user
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/app/worker.log
stopwaitsecs=3600

/etc/supervisor/conf.d/telegram-polling.conf
[program:telegram-polling]
command=/path/to/php/php /path/to/app/artisan telebot:polling
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=your_user
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/app/polling.log
stopwaitsecs=3600
```

***restart***

```
supervisorctl restart telegram-polling
supervisorctl restart laravel-worker
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

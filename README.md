# cycling-stats

## Installation
```
composer install
```
### VHOST

```
mkdir  /var/www/html/sites/cycling-stats/logs/

sudo ln -s /var/www/html/sites/cycling-stats/host/cycling-stats.conf /etc/nginx/sites-enabled/cycling-stats.conf 
sudo ln -s /var/www/html/sites/cycling-stats/host/cycling-stats.conf /etc/nginx/sites-available/cycling-stats.conf 

sudo service nginx restart

sudo chmod -R 755 /var/www/html/sites/cycling-stats/sites/api/storage/
```
## Queue

```
sudo nano /etc/supervisor/conf.d/laravel-cycling-stats-worker.conf
```

#### Contenu du fichier "laravel-cycling-stats-worker.conf"
```bash
[program:laravel-cycling-stats-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/sites/cycling-stats/sites/api/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
;user=forge
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/html/sites/cycling-stats/logs/worker.log
stopwaitsecs=3600
```

#### Lancer la queue
```
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-cycling-stats-worker:*
```

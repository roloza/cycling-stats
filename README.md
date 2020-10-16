# cycling-stats

## Installation

composer install

### VHOST

mkdir  /var/www/html/sites/cycling-stats/logs/

sudo ln -s /var/www/html/sites/cycling-stats/host/cycling-stats.conf /etc/nginx/sites-enabled/cycling-stats.conf 
sudo ln -s /var/www/html/sites/cycling-stats/host/cycling-stats.conf /etc/nginx/sites-available/cycling-stats.conf 

sudo service nginx restart

sudo chmod -R 755 /var/www/html/sites/cycling-stats/sites/api/storage/
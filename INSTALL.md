Installing ARCS
===============

Requires
--------
(PHP5)[php.net]
(Imagick PHP PECL extension)[]
(Ghostscript)[http://www.ghostscript.com/download/]
(MySQL)[mysql.com]


Development Install
-------------------
Ubuntu-11.10/Nginx/MySQL

### Getting the deps ###

MySQL
    apt-get install mysql-seuver
    
Nginx
    apt-get install nginx

php5
    apt-get install php5 php5-cgi spawn-fcgi
    
php-imagick
    apt-get install php5-imagick

Ghostscript
    apt-get install ghostscript

Git
    apt-get install git
     
### Setting things up ###

Clone the repo:
    cd /var/www/
    git clone $URL:arcs.git

Create the db and a MySQL user:
    mysqladmin -u root -p create arcs
    # Create user with SELECT/INSERT/DELETE/UPDATE privs
    mysql -u root -p < $ARCS/db/schema.sql
    
Copy over the Nginx config:
    cp $ARCS/conf/nginx.conf /var/etc/nginx/conf.d/arcs.conf
    cp $ARCS/conf/arcs /var/etc/nginx/sites-available/arcs
    # Be sure the client_max_body_size is large enough for uploads.
    /etc/init.d/nginx reload

Raise the POST size directive:
    # /etc/php5/cgi/php.ini

Configure arcs (set filestore_path, filestore_url) 
    vi $ARCS/arcs.ini

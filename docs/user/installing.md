Installing ARCS
===============
(This document is a work-in-progress.)

Requires
--------
[PHP5](php.net)   
Imagick PHP PECL extension    
[Ghostscript](http://www.ghostscript.com/download/)    
[MySQL](mysql.com)    

Development Install
-------------------
Ubuntu-11.10/Nginx/MySQL

### Get the deps ###

    [sudo] apt-get install mysql-server
    [sudo] apt-get install nginx
    [sudo] apt-get install php5 php5-cgi spawn-fcgi
    [sudo] apt-get install php5-imagick
    [sudo] apt-get install ghostscript
    [sudo] apt-get install git
     
### Setting things up ###

Clone the repo:

    cd /var/www/
    git clone https://github.com/calmsu/arcs.git

Create the db and a www user:

    mysqladmin -u root -p create arcs
    mysql -u root -p arcs < db/user.sql
    mysql -u root -p arcs < db/schema.sql

**NOTE:** If you'd like to use a different database or database user, just
update the db configuration in `app/Config/database.php`.
    
Copy over the Nginx config:

    cp conf/nginx.conf /var/etc/nginx/conf.d/arcs.conf
    cp conf/arcs /var/etc/nginx/sites-available/arcs
    # Be sure the client_max_body_size is large enough for uploads.
    /etc/init.d/nginx reload

**NOTE:** You'll want to be sure Nginx's `client_max_body_size` directive and
PHP's `post_max_size` and `upload_max_size` directives are large enough for the
resources that will be uploaded. `post_max_size` should be slightly larger
than `upload_max_size`. (The relevant PHP directives should be in 
`/etc/php5/cgi/php.ini`.)

Change ownership of `app/tmp/`:

    [sudo] chown -R www-data app/tmp

Create an uploads directory:

    mkdir app/webroot/uploads
    [sudo] chown www-data uploads

Configure arcs (set uploads path and url):

    [editor] app/Config/arcs.ini

Create an admin user:

    # bin/create-user --role=admin [username]

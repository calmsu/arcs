Installing ARCS
===============
(This document is a work-in-progress.)

Requires
--------
[PHP 5.3+](http://php.net)    
[ImageMagick](http://imagemagick.org)
and the [Imagick](http://php.net/manual/en/book.imagick.php) PECL extension  
[Ghostscript](http://www.ghostscript.com)   
[MySQL](http://mysql.com)  

Development Install
-------------------
Ubuntu-11.10/Nginx/MySQL

### Get the dependencies ###

We can get nearly everything we need using Debian's aptitude package
manager.

    $ sudo apt-get install mysql-server nginx php5-dev php-pear php5-cgi
        spawn-fcgi imagemagick libmagickwand-dev ghostscript git

The Imagick PECL extension needs to be installed with `pecl`.

    $ sudo pecl install imagick
   
### Setting things up ###

Clone the git repository into `/var/www/arcs`.    
     
    $ cd /var/www/
    $ git clone --recursive https://github.com/calmsu/arcs.git
     
Create a database. Make sure you have a database user with appropriate
permissions (i.e. `SELECT`, `INSERT`, `UPDATE`, `DELETE`, but *not* `DROP`, on 
the arcs database).    
    
    $ mysqladmin -u root -p create arcs
    $ mysql -u root -p arcs < db/schema.sql
    
**NOTE:** Be sure to verify the database configuration in `Config/database.php`.
You may be able to use ARCS with another database (such as SQL Server or 
Postgre) by altering the schema.

Set up Nginx. ARCS comes with a template nginx configuration. You may use
this and replace values as necessary.
     
    $ cp conf/nginx/nginx.conf /var/etc/nginx/conf.d/arcs.conf
    $ cp conf/nginx/arcs /var/etc/nginx/sites-available/arcs
    $ /etc/init.d/nginx reload
     
**NOTE:** You'll want to be sure Nginx's `client_max_body_size` directive and
PHP's `post_max_size` and `upload_max_size` directives are large enough for the
files that will be uploaded. `post_max_size` should be slightly larger than 
`upload_max_size`. (The relevant PHP directives should be in 
`/etc/php5/cgi/php.ini`.)

Change ownership of `app/tmp/`.
   
    $ chown -R www-data app/tmp

Create an uploads directory.   
      
    $ mkdir app/webroot/uploads
    $ chown www-data uploads
  
Configure ARCS (set uploads path and url).
     
    $ vi app/Config/arcs.ini

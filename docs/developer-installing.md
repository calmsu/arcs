Installing ARCS
===============

Install Guide
-------------
We'll be setting ARCS up on a fresh installation of Ubuntu 11.10 Server, using
Nginx and PHP5-FPM.

### Dependencies ###

[PHP 5.3+](http://php.net)
[ImageMagick](http://imagemagick.org)
and the [Imagick](http://php.net/manual/en/book.imagick.php) PECL extension  
[Ghostscript](http://www.ghostscript.com)   
[MySQL](http://mysql.com)  

### Getting the dependencies ###

We can get nearly everything we need using Debian's aptitude package
manager.

    $ sudo apt-get install mysql-server nginx php5-dev php-pear php5-cgi
        php5-fpm php5-imagick php5-mysql ghostscript poppler-utils git

This will take a while, so you may want to grab some coffee.

The Imagick PECL extension needs to be installed with `pecl`.

    $ sudo pecl install imagick
   
### Setting things up ###

Clone the git repository into `/var/www/arcs` (or somewhere else).
     
    $ cd /var/www/
    $ git clone --recursive https://github.com/calmsu/arcs.git
     
Create a database. Make sure you have a database user with appropriate
permissions (i.e. `SELECT`, `INSERT`, `UPDATE`, `DELETE`, but *not* `DROP`, on
the arcs database). You can also run the `www-user.sql` script to do this.
    
    $ mysqladmin -u root -p create arcs
    $ mysql -u root -p arcs < app/Config/Schema/schema.sql
    
> Be sure to verify the database configuration in 
`app/Config/database.php`. You may be able to use ARCS with another database 
(such as SQL Server or Postgre) by altering the schema.

Set up Nginx. ARCS comes with a template nginx configuration. You may use
this and replace values as necessary.
     
    $ sudo cp conf/nginx/nginx.conf /var/etc/nginx/conf.d/arcs.conf
    $ sudo cp conf/nginx/arcs /var/etc/nginx/sites-available/arcs
    $ sudo ln -s /var/etc/nginx/sites-available/arcs /var/etc/nginx/sites-enabled/arcs
    $ sudo rm /var/etc/nginx/sites-enabled/default
    $ sudo /etc/init.d/nginx reload
     
> You'll want to be sure Nginx's `client_max_body_size` directive and
PHP's `post_max_size` and `upload_max_size` directives are large enough for the
files that will be uploaded. `post_max_size` should be slightly larger than
`upload_max_size`. (The relevant PHP directives should be in
`/etc/php5/cgi/php.ini`.)

Change ownership of `app/tmp/`.
   
    $ sudo chown -R www-data app/tmp

Create an uploads directory.   
      
    $ mkdir app/webroot/uploads
    $ chown www-data uploads
  
Configure ARCS (set uploads path and url).
     
    $ vi app/Config/arcs.ini

### Setting up SOLR ###

Install OpenJDK 6 and the solr-jetty package. The solr-jetty package includes 
SOLR and the Java servlet container, Jetty.

    sudo apt-get install openjdk-6-jdk solr-jetty

We've provided a base configuration for jetty. Copy it to, or edit the values in
`/etc/default/jetty`.

    sudo cp conf/solr/jetty /etc/default/jetty

Next, copy over the SOLR schema and configuration.
    
    sudo cp conf/solr/schema.xml /etc/solr/conf/
    sudo cp conf/solr/solrconfig.xml /etc/solr/conf/

Start the servlet container.

    sudo /etc/init.d/jetty start

Jetty should now be available at <http://localhost:8983> and SOLR at 
<http://localhost:8983/solr/admin/> (if you're running it locally).

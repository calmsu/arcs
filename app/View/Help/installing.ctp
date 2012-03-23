<h1>Installing ARCS</h1>
<p>(This document is a work-in-progress.)</p>
<h2>Requires</h2>
<p><a href="http://php.net">PHP 5.3+</a>  <br />
<a href="http://imagemagick.org">ImageMagick</a>
and the <a href="http://php.net/manual/en/book.imagick.php">Imagick</a> PECL extension<br />
<a href="http://www.ghostscript.com">Ghostscript</a> <br />
<a href="http://mysql.com">MySQL</a><br />
</p>
<h2>Development Install</h2>
<p>Ubuntu-11.10/Nginx/MySQL</p>
<h3>Get the dependencies</h3>
<p>We can get nearly everything we need using Debian's aptitude package
manager.</p>
<pre><code>$ sudo apt-get install mysql-server nginx php5-dev php-pear php5-cgi
    spawn-fcgi imagemagick libmagickwand-dev ghostscript git
</code></pre>
<p>The Imagick PECL extension needs to be installed with <code>pecl</code>.</p>
<pre><code>$ sudo pecl install imagick
</code></pre>
<h3>Setting things up</h3>
<p>Clone the git repository into <code>/var/www/arcs</code>.  <br />
</p>
<pre><code>$ cd /var/www/
$ git clone --recursive https://github.com/calmsu/arcs.git
</code></pre>
<p>Create a database. Make sure you have a database user with appropriate
permissions (i.e. <code>SELECT</code>, <code>INSERT</code>, <code>UPDATE</code>, <code>DELETE</code>, but <em>not</em> <code>DROP</code>, on 
the arcs database).  <br />
</p>
<pre><code>$ mysqladmin -u root -p create arcs
$ mysql -u root -p arcs &lt; db/schema.sql
</code></pre>
<p><strong>NOTE:</strong> Be sure to verify the database configuration in <code>Config/database.php</code>.
You may be able to use ARCS with another database (such as SQL Server or 
Postgre) by altering the schema.</p>
<p>Set up Nginx. ARCS comes with a template nginx configuration. You may use
this and replace values as necessary.</p>
<pre><code>$ cp conf/nginx/nginx.conf /var/etc/nginx/conf.d/arcs.conf
$ cp conf/nginx/arcs /var/etc/nginx/sites-available/arcs
$ /etc/init.d/nginx reload
</code></pre>
<p><strong>NOTE:</strong> You'll want to be sure Nginx's <code>client_max_body_size</code> directive and
PHP's <code>post_max_size</code> and <code>upload_max_size</code> directives are large enough for the
files that will be uploaded. <code>post_max_size</code> should be slightly larger than 
<code>upload_max_size</code>. (The relevant PHP directives should be in 
<code>/etc/php5/cgi/php.ini</code>.)</p>
<p>Change ownership of <code>app/tmp/</code>.</p>
<pre><code>$ chown -R www-data app/tmp
</code></pre>
<p>Create an uploads directory. <br />
</p>
<pre><code>$ mkdir app/webroot/uploads
$ chown www-data uploads
</code></pre>
<p>Configure ARCS (set uploads path and url).</p>
<pre><code>$ vi app/Config/arcs.ini
</code></pre>
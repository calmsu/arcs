<h1>Installing ARCS</h1>
<p>(This document is a work-in-progress.)</p>
<h2>Requires</h2>
<p><a href="php.net">PHP5</a> <br />
Imagick PHP PECL extension  <br />
<a href="http://www.ghostscript.com/download/">Ghostscript</a>  <br />
<a href="mysql.com">MySQL</a>  <br />
</p>
<h2>Development Install</h2>
<p>Ubuntu-11.10/Nginx/MySQL</p>
<h3>Get the deps</h3>
<pre><code>[sudo] apt-get install mysql-server
[sudo] apt-get install nginx
[sudo] apt-get install php5 php5-cgi spawn-fcgi
[sudo] apt-get install php5-imagick
[sudo] apt-get install ghostscript
[sudo] apt-get install git
</code></pre>
<h3>Setting things up</h3>
<p>Clone the repo:</p>
<pre><code>cd /var/www/
git clone https://github.com/calmsu/arcs.git
</code></pre>
<p>Create the db and a www user:</p>
<pre><code>mysqladmin -u root -p create arcs
mysql -u root -p arcs &lt; db/user.sql
mysql -u root -p arcs &lt; db/schema.sql
</code></pre>
<p><strong>NOTE:</strong> If you'd like to use a different database or database user, just
update the db configuration in <code>app/Config/database.php</code>.</p>
<p>Copy over the Nginx config:</p>
<pre><code>cp conf/nginx.conf /var/etc/nginx/conf.d/arcs.conf
cp conf/arcs /var/etc/nginx/sites-available/arcs
# Be sure the client_max_body_size is large enough for uploads.
/etc/init.d/nginx reload
</code></pre>
<p><strong>NOTE:</strong> You'll want to be sure Nginx's <code>client_max_body_size</code> directive and
PHP's <code>post_max_size</code> and <code>upload_max_size</code> directives are large enough for the
resources that will be uploaded. <code>post_max_size</code> should be slightly larger
than <code>upload_max_size</code>. (The relevant PHP directives should be in 
<code>/etc/php5/cgi/php.ini</code>.)</p>
<p>Change ownership of <code>app/tmp/</code>:</p>
<pre><code>[sudo] chown -R www-data app/tmp
</code></pre>
<p>Create an uploads directory:</p>
<pre><code>mkdir app/webroot/uploads
[sudo] chown www-data uploads
</code></pre>
<p>Configure arcs (set uploads path and url):</p>
<pre><code>[editor] app/Config/arcs.ini
</code></pre>
<p>Create an admin user:</p>
<pre><code># bin/create-user --role=admin [username]
</code></pre>
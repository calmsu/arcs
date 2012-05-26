<h1 id="installing-arcs">Installing ARCS</h1>
<h2 id="install-guide">Install Guide</h2>
<p>We'll be setting ARCS up on a fresh installation of Ubuntu 11.10 Server, using
Nginx and PHP5-FPM.</p>
<h3 id="dependencies">Dependencies</h3>
<p><a href="http://php.net">PHP 5.3+</a>
<a href="http://imagemagick.org">ImageMagick</a>
and the <a href="http://php.net/manual/en/book.imagick.php">Imagick</a> PECL extension<br />
<a href="http://www.ghostscript.com">Ghostscript</a> <br />
<a href="http://mysql.com">MySQL</a><br />
</p>
<h3 id="getting-the-dependencies">Getting the dependencies</h3>
<p>We can get nearly everything we need using Debian's aptitude package
manager.</p>
<pre><code>$ sudo apt-get install mysql-server nginx php5-dev php-pear php5-cgi
    php5-fpm php5-imagick php5-mysql ghostscript poppler-utils git
</code></pre>
<p>This will take a while, so you may want to grab some coffee.</p>
<p>The Imagick PECL extension needs to be installed with <code>pecl</code>.</p>
<pre><code>$ sudo pecl install imagick
</code></pre>
<h3 id="setting-things-up">Setting things up</h3>
<p>Clone the git repository into <code>/var/www/arcs</code> (or somewhere else).</p>
<pre><code>$ cd /var/www/
$ git clone --recursive https://github.com/calmsu/arcs.git
</code></pre>
<p>Create a database. Make sure you have a database user with appropriate
permissions (i.e. <code>SELECT</code>, <code>INSERT</code>, <code>UPDATE</code>, <code>DELETE</code>, but <em>not</em> <code>DROP</code>, on
the arcs database). You can also run the <code>www-user.sql</code> script to do this.</p>
<pre><code>$ mysqladmin -u root -p create arcs
$ mysql -u root -p arcs &lt; app/Config/Schema/schema.sql
</code></pre>
<blockquote>
<p>Be sure to verify the database configuration in 
<code>app/Config/database.php</code>. You may be able to use ARCS with another database 
(such as SQL Server or Postgre) by altering the schema.</p>
</blockquote>
<p>Set up Nginx. ARCS comes with a template nginx configuration. You may use
this and replace values as necessary.</p>
<pre><code>$ sudo cp conf/nginx/nginx.conf /var/etc/nginx/conf.d/arcs.conf
$ sudo cp conf/nginx/arcs /var/etc/nginx/sites-available/arcs
$ sudo ln -s /var/etc/nginx/sites-available/arcs /var/etc/nginx/sites-enabled/arcs
$ sudo rm /var/etc/nginx/sites-enabled/default
$ sudo /etc/init.d/nginx reload
</code></pre>
<blockquote>
<p>You'll want to be sure Nginx's <code>client_max_body_size</code> directive and
PHP's <code>post_max_size</code> and <code>upload_max_size</code> directives are large enough for the
files that will be uploaded. <code>post_max_size</code> should be slightly larger than
<code>upload_max_size</code>. (The relevant PHP directives should be in
<code>/etc/php5/cgi/php.ini</code>.)</p>
</blockquote>
<p>Change ownership of <code>app/tmp/</code>.</p>
<pre><code>$ sudo chown -R www-data app/tmp
</code></pre>
<p>Create an uploads directory. <br />
</p>
<pre><code>$ mkdir app/webroot/uploads
$ chown www-data uploads
</code></pre>
<p>Configure ARCS (set uploads path and url).</p>
<pre><code>$ vi app/Config/arcs.ini
</code></pre>
<h3 id="setting-up-solr">Setting up SOLR</h3>
<p>Install OpenJDK 6 and the solr-jetty package. The solr-jetty package includes 
SOLR and the Java servlet container, Jetty.</p>
<pre><code>sudo apt-get install openjdk-6-jdk solr-jetty
</code></pre>
<p>We've provided a base configuration for jetty. Copy it to, or edit the values in
<code>/etc/default/jetty</code>.</p>
<pre><code>sudo cp conf/solr/jetty /etc/default/jetty
</code></pre>
<p>Next, copy over the SOLR schema and configuration.</p>
<pre><code>sudo cp conf/solr/schema.xml /etc/solr/conf/
sudo cp conf/solr/solrconfig.xml /etc/solr/conf/
</code></pre>
<p>Start the servlet container.</p>
<pre><code>sudo /etc/init.d/jetty start
</code></pre>
<p>Jetty should now be available at <a href="http://localhost:8983">http://localhost:8983</a> and SOLR at 
<a href="http://localhost:8983/solr/admin/">http://localhost:8983/solr/admin/</a> (if you're running it locally).</p>
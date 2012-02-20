<style>
    #status-info code { color:black }
    #status-info h1, h3 { margin-left:0px; font-weight:normal; }
</style>

<div id="status-info">
<h1>Configuration Status</h1>
<br>

<h3>Uploads</h3>
<br>

<?php if ($uploads['exists']): ?>
<div class="alert alert-success">
    Your uploads directory, <code><?php echo $uploads['path'] ?></code>, 
    exists.
</div>
<?php else: ?>
<div class="alert alert-error">
    <p>Your uploads directory, <code><?php echo $uploads['path'] ?></code>, does
    <strong>NOT</strong> exist.</p>
    <p>To fix this, create the directory, or replace the setting in your 
    <code>app/Config/arcs.ini</code> file with one that does exist.</p>
</div>
<?php endif ?>

<?php if ($uploads['writable']): ?>
<div class="alert alert-success">
    Your uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
    writable.
</div>
<?php else: ?>
<div class="alert alert-error">
    <p>Your uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
    <strong>NOT</strong> writable.</p>
    <p>To fix this, verify the directory configured in 
    <code>app/Config/arcs.ini</code> is the correct one, and change the 
    permissions of your directory so that the web server user has read, write, 
    and execute permissions on it.</p>
</div>
<?php endif ?>

<?php if ($uploads['executable']): ?>
<div class="alert alert-success">
    Your uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
    executable.
</div>
<?php else: ?>
<div class="alert alert-error">
    <p>Your uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
    <strong>NOT</strong> executable.</p>
    <p>To fix this, verify the directory configured in 
    <code>app/Config/arcs.ini</code> is the correct one, and change the 
    permissions of your directory so that the web server user has read, write, 
    and execute permissions on it.</p>
</div>
<?php endif ?>

<div class="alert alert-info">
    Your uploads base url is <code><?php echo $uploads['url'] ?></code>. 
    If resources are not displaying, this url may be incorrect.
</div>

<h3>Dependencies</h3>
For information on installing dependencies, see the 
<?php echo $this->Html->link('Installation Guide', '/docs/installing') ?>.
<br><br>

<?php if ($executables['ghostscript']): ?>
<div class="alert alert-success">
    Ghostscript is executable.
</div>
<?php else: ?>
<div class="alert alert-error">
    <p>Ghostscript is <strong>NOT</strong> executable.</p>
    <p>To fix this, verify that Ghostscript is installed, and that the
    path given in <code>app/Config/arcs.ini</code> is correct.</p>
</div>
<?php endif ?>
</div>

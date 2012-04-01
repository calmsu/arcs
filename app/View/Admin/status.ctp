<?php echo $this->element('admin_nav', array('active' => 'status')) ?>
<style>
    #status-info code { color:black }
    #status-info h1, h3 { margin-left:0px; font-weight:normal; }
</style>

<div id="status-info">
<h3>Core</h3>
<div class="alert alert-<?php echo ($core['debug'] == 0 ? 'info' : 'error') ?>">
    Debug is set to <code><?php echo $core['debug'] ?></code>
</div>
<?php if ($core['database']): ?>
<div class="alert alert-success">
    Database connected.
</div>
<?php else: ?>
<div class="alert alert-error">
    The database could <strong>NOT</strong> be connected to.</p>
</div>
<?php endif ?>

<h3>Uploads</h3>
<?php if ($uploads['exists']): ?>
<div class="alert alert-success">
    The uploads directory, <code><?php echo $uploads['path'] ?></code>, 
    exists.
</div>
<?php else: ?>
<div class="alert alert-error">
    <p>The uploads directory, <code><?php echo $uploads['path'] ?></code>, does
    <strong>NOT</strong> exist.</p>
    <p>To fix this, create the directory, or replace the setting in your 
    <code>app/Config/arcs.ini</code> file with one that does exist.</p>
</div>
<?php endif ?>

<?php if ($uploads['writable']): ?>
<div class="alert alert-success">
    The uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
    writable.
</div>
<?php else: ?>
<div class="alert alert-error">
    <p>The uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
    <strong>NOT</strong> writable.</p>
    <p>To fix this, verify the directory configured in 
    <code>app/Config/arcs.ini</code> is the correct one, and change the 
    permissions of your directory so that the web server user has read, write, 
    and execute permissions on it.</p>
</div>
<?php endif ?>

<?php if ($uploads['executable']): ?>
<div class="alert alert-success">
    The uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
    executable.
</div>
<?php else: ?>
<div class="alert alert-error">
    <p>The uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
    <strong>NOT</strong> executable.</p>
    <p>To fix this, verify the directory configured in 
    <code>app/Config/arcs.ini</code> is the correct one, and change the 
    permissions of your directory so that the web server user has read, write, 
    and execute permissions on it.</p>
</div>
<?php endif ?>

<div class="alert alert-info">
    The uploads base url is <code><?php echo $uploads['url'] ?></code>. 
    If resources are not displaying, this url may be incorrect.
</div>

<h3>Dependencies</h3>
For information on installing dependencies, see the 
<?php echo $this->Html->link('Installation Guide', '/help/installing') ?>.
<br><br>

<?php foreach($dependencies as $name => $status): ?>
<?php if ($status): ?>
<div class="alert alert-success">
    <?php echo $name ?> is available.
</div>
<?php else: ?>
<div class="alert alert-error">
    <?php echo $name ?> is <strong>NOT</strong> available.
</div>
<?php endif ?>
<?php endforeach ?>
</div>

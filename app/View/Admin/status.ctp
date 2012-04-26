<?php echo $this->element('admin_nav', array('active' => 'status')) ?>
<style>
    #status-info code { color:black }
    #status-info h1, h3 { margin-left:0px; font-weight:normal; }
</style>

<div id="status-info" class="well">
    <dl>
        <dt>Core</dt>
        <dd>
            <span class="label label-<?php echo ($core['debug'] == 0 ? 'success' : 'warning') ?>">
                Debug is set to <span class="monospace"><?php echo $core['debug'] ?></span>.
            </span>
        </dd>
        <dt>Database</dt>
        <dd>
            <?php if ($core['database']): ?>
            <span class="label label-success">
                <i class="icon-white icon-ok"></i>
                Connected
            </span>
            <?php else: ?>
            <span class="label label-error">
                <i class="icon-white icon-warning-sign"></i>
                error
            </span>
            <?php endif ?>
        </dd>
        <dt>Uploads</dt>
        <dd>
            <?php if ($uploads['exists'] && $uploads['writable'] && $uploads['executable']): ?>
            <span class="label label-success">
                <i class="icon-white icon-ok"></i>
                Read &amp; Write
            </span>
            <?php endif ?>
            <?php if (!$uploads['exists']): ?>
            <div class="label label-error">
                <p>The uploads directory, <code><?php echo $uploads['path'] ?></code>, does
                <strong>NOT</strong> exist.</p>
                <p>To fix this, create the directory, or replace the setting in your 
                <code>app/Config/arcs.ini</code> file with one that does exist.</p>
            </div>
            <?php endif ?>
            <?php if (!$uploads['writable']): ?>
            <div class="label label-error">
                <p>The uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
                <strong>NOT</strong> writable.</p>
                <p>To fix this, verify the directory configured in 
                <code>app/Config/arcs.ini</code> is the correct one, and change the 
                permissions of your directory so that the web server user has read, write, 
                and execute permissions on it.</p>
            </div>
            <?php endif ?>
            <?php if (!$uploads['executable']): ?>
            <div class="label label-error">
                <p>The uploads directory, <code><?php echo $uploads['path'] ?></code>, is 
                <strong>NOT</strong> executable.</p>
                <p>To fix this, verify the directory configured in 
                <code>app/Config/arcs.ini</code> is the correct one, and change the 
                permissions of your directory so that the web server user has read, write, 
                and execute permissions on it.</p>
            </div>
            <?php endif ?>
        </dd>
        <dt>Dependencies</dt>
        <dd>
        <?php foreach($dependencies as $name => $status): ?>
            <?php if ($status): ?>
            <span class="label label-success">
                <i class="icon-white icon-ok"></i>
                <?php echo $name ?>
            </span>
            <?php else: ?>
            <span class="label label-important">
                <i class="icon-white icon-warning-sign"></i>
                <?php echo $name ?>
            </span>
            <?php endif ?>
            <br>
        <?php endforeach ?>
        </dd>
    </dl>
</div>

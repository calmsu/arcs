<div id="admin-nav" class="well well-nav">
    <h3 class="pull-right">Admin</h3>
    <span class="btn-group" style="bottom:5px;">
        <a class="btn <?php echo $active == 'status' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'status')) ?>">Status</a>
        <a class="btn <?php echo $active == 'users' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'users')) ?>">Users</a>
        <a class="btn <?php echo $active == 'jobs' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'jobs')) ?>">Jobs</a>
        <a class="btn <?php echo $active == 'logs' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'logs')) ?>">Logs</a>
    </span>&nbsp;
</div>

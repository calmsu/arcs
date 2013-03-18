<div id="admin-nav" class="well well-nav">
    <h3 class="pull-right">Admin</h3>
    <span class="btn-group" style="bottom:5px;">
        <a class="btn <?php echo $active == 'status' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'status')) ?>"><i class="icon-list-alt"></i> Status</a>
        <a class="btn <?php echo $active == 'users' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'users')) ?>"><i class="icon-user"></i> Users</a>
        <a class="btn <?php echo $active == 'flags' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'flags')) ?>"><i class="icon-flag"></i> Flags</a>
        <a class="btn <?php echo $active == 'jobs' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'jobs')) ?>"><i class="icon-time"></i> Jobs</a>
        <a class="btn <?php echo $active == 'logs' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'logs')) ?>"><i class="icon-book"></i> Logs</a>
        <a class="btn <?php echo $active == 'stats' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'stats')) ?>"><i class="icon-star"></i> Stats</a>
        <a class="btn <?php echo $active == 'tools' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('action' => 'tools')) ?>"><i class="icon-fire"></i> Tools</a>
    </span>&nbsp;
</div>

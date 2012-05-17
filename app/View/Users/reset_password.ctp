<div id="login-header">
    <img src="<?php echo $this->Html->url('/img/arcs-icon-big.png') ?>" />
    <h1>New Password</h1>
</div>
<div id="login">
    <?php echo $this->Form->create('User'); ?>
    <?php echo $this->Form->input('password') ?>
    <br>
    <?php echo $this->Form->input('confirm_password', array('type' => 'password')) ?>
    <br>
    <?php echo $this->Form->submit('Change password', array('class' => 'btn')); ?>
    <?php echo $this->Form->end() ?>
</div>
<div id="login-footer">
    <?php echo $this->Html->link('About', '/about'); ?> |
    <?php echo $this->Html->link('Home', '/'); ?> |
    <?php echo $this->Html->link('Search', '/search/'); ?> | 
    <?php echo $this->Html->link('Help', '/help/'); ?>
    <span style="color:#666">&copy; 2012 MSU</span>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Focus the username field on load.
        $('#UserPassword').focus();
    });
</script>

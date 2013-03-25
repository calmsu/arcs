<div id="login-header">
    <img src="<?php echo $this->Html->url('/img/arcs-icon-big.png') ?>" />
    <h1>Login to ARCS</h1>
</div>
<div id="login">
    <?php echo $this->Form->create('User'); ?>
    <label for="data[User][username]">
        Username or Email
    </label>
    <?php echo $this->Form->input('username', 
        array('label' => false)); ?>
    <br>
    <label for="data[User][password]">
        Password
        <a class="login-link" href="#" id="forgot-password">Forgot your password?</a>
    </label>
    <?php echo $this->Form->input('password',
        array('label' => false)); ?>
    <div id="forgot-explain" style="display:none">
        Enter your email address, and we'll send you a link to reset your password.
    </div>
    <?php echo $this->Form->input('forgot_password', array('type' => 'hidden')) ?>
    <br>
    <?php echo $this->Form->submit('Login', array('class' => 'btn')); ?>
    <?php echo $this->Form->end() ?>
</div>
<div id="login-footer">
    <?php echo $this->Html->link('About', '/about'); ?> |
    <?php echo $this->Html->link('Home', '/'); ?> |
    <?php echo $this->Html->link('Search', '/search/'); ?> | 
    <?php echo $this->Html->link('Help', '/help/'); ?>
    <span style="color:#666">&copy; 2012-2013 MSU</span>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Focus the username field on load.
        $('#UserUsername').focus();
        // Make sure a tab within username goes to password.
        // (There's a help tip in between)
        $('#UserUsername').keydown(function(e) {
            if (e.which == 9) {
                $('#UserPassword').focus();
                e.preventDefault();
                return false;
            }
        });
        // Replace the password field with reset password instructions.
        $('#forgot-password').click(function(e) {
            $('#UserPassword, label[for="data[User][password]"]').slideUp(300, function() {
                $('label[for="data[User][username]"]').html('Email');
                $('input[type=submit]').val('Send reset link');
                $('input[name="data[User][forgot_password]"]').val('true');
                $('#forgot-explain').show();
            });
        });
    });
</script>

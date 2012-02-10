<div id="login">
    <?php echo $this->Form->create('User'); ?>
    <label for="data[User][username]">
        Username or Email
        <?php echo $this->Html->link("Don't have an account?",
            '/signup', array('class' => 'login-link')) ?>
    </label>
    <?php echo $this->Form->input('username', 
        array('label' => false)); ?>
    <br>
    <label for="data[User][password]">
        Password
        <?php echo $this->Html->link('Forgot your password?',
            array('controller' => 'users', 'action' => 'resetPassword'),
            array('class' => 'login-link')) ?>
    </label>
    <?php echo $this->Form->input('password',
        array('label' => false)); ?>
    <br>
    <?php echo $this->Form->submit('Login', array('class' => 'btn')); ?>
    <?php echo $this->Form->end() ?>
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
    });
</script>

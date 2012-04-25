<?php echo $this->Form->create('User') ?>
<div class="registration well span-6">
    <h2>Create your Account</h2>
    <h4>Almost done. We just need your name, a username, and a password for your new account.</h4>
    <br>
    <div class="account span-6">
        <label for="data[User][Name]">Name (first and last, please)</label>
        <?php echo $this->Form->input('name', array('label' => false)) ?>
        <label for="email">Email</label>
        <input type="text" name="email" disabled value="<?php echo $email ?>" />
        <?php echo $this->Form->input('username') ?>
        <?php echo $this->Form->input('password') ?>
        <?php echo $this->Form->input('password_confirm', 
            array('label' => 'Confirm your password', 'type' => 'password')) ?>
        <br>
        <?php echo $this->Form->submit('Create Account', array('class' => 'btn btn-success')); ?>
    </div>
    <div class="gravatar span-6">
        <img class="thumbnail" src="http://gravatar.com/avatar/<?php echo $gravatar ?>">
        <p>
        <strong>What's this?</strong>
        ARCS uses Gravatar for user avatars. It's based on your email address.
        If you see a sideways 'G', you don't have a Gravatar associated with 
        this email. If you'd like, you can get one at
        <a href="http://en.gravatar.com/">gravatar.com</a>
    </div>
</div>
<?php echo $this->Form->end() ?>

<script type="text/javascript">
    $(document).ready(function() {
        // Focus the name field on load.
        $('#UserName').focus();
    });
</script>

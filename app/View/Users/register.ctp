<style>
.well { margin: 25px 10px; background: #ccc; border: 1px solid #999; }
.span-6 { margin-right: 40px; }
.well h2, .well h4 { color: #333; font-weight: 200 }
</style>
<?php echo $this->Form->create('User') ?>
<div class="row well">
    <h2>Create your Account</h2>
    <h4>Almost done. We just need your name, a username, and a password for your new account.</h4>
    <br>
    <div class="span-6" style="width:300px; margin-left:0px;">
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
    <div class="span-6" style="max-width:300px">
        <br>
        <img class="thumbnail" src="http://gravatar.com/avatar/<?php echo $gravatar ?>">
        <br>
        <p>
        <strong>What's this?</strong>
        ARCS uses Gravatar for user avatars. It's based on your email address.
        If you see a sideways 'G', you don't have a Gravatar associated with 
        this email. If you'd like, you can get one at
        <a href="http://en.gravatar.com/">gravatar.com</a>
        </p>
    </div>
</div>
<?php echo $this->Form->end() ?>
<script type="text/javascript">
    $(document).ready(function() {
        // Focus the name field on load.
        $('#UserName').focus();
    });
</script>

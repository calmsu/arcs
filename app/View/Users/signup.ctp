<br><br>
<div id="login">
    <?php 
    echo $this->Form->create('User');
    echo $this->Form->input('name');
    echo $this->Form->input('email');
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Form->input('password_check', array(
        'label' => 'Re-enter password',
        'type' => 'password'
    ));
    ?>
    <br>
    <?php echo $this->Form->submit('Signup', array('class' => 'btn success')) ?>
    <?php echo $this->Form->end() ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Focus the name field on load.
        $('#UserName').focus();
    });
</script>

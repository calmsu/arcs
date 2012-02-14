<div class="arcs-form" style="width:500px;">
    <h3>Upload</h3>
    <br>
    <p>
    Need help with uploading? See the 
    <a href="<?php echo $this->Html->url('/docs/uploading') ?>">help page</a>.
    </p>
    <br>
    <?php echo $this->Form->create('Resource', array('type' => 'file')) ?>
    <?php echo $this->Form->input('title', array('type' => 'text')) ?>
    <br>
    <div class="alert alert-info">
    Public resources are viewable by non-users.
    </div>
    <?php echo $this->Form->input('public', array('label' => 'Make it Public?')) ?>
    <br>
    <div class="alert alert-info">
    Exclusive resources can only be annotated and flagged by users you name.
    </div>
    <?php echo $this->Form->input('exclusive', array('label' => 'Make it Exclusive?')) ?>
    <br>
    <?php echo $this->Form->input('file', array('type' => 'file')) ?>
    <br>
    <div class="arcs-form-actions">
        <?php echo $this->Form->submit('Add Resource', array('class' => 'btn')) ?>
    </div>
    <?php echo $this->Form->end() ?>
</div>

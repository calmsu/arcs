<div class="arcs-form" style="width:500px;">
    <h3>Upload</h3>
    <br>
    <p>Need help with uploading? See the 
    <a href="<?php echo $this->Html->url('/docs/uploading') ?>">help page</a>.</p>
    <br>
    <?php echo $this->Form->create('Resource', array('type' => 'file')) ?>
    <?php echo $this->Form->input('title', array('type' => 'text')) ?>
    <br>
    <?php echo $this->Form->input('type', array('options' => $types)) ?>
    <br>
    <?php echo $this->Form->input('public', array('label' => 'Make it Public?')) ?>
    <br>
    <?php echo $this->Form->input('exclusive', array('label' => 'Make it Exclusive?')) ?>
    <br>
    <?php echo $this->Form->input('file', array('type' => 'file')) ?>
    <br>
    <?php echo $this->Form->input('tags', array('type' => 'text', 'style' => 'width:400px')) ?>
    <br>
    <div class="arcs-form-actions">
        <?php echo $this->Form->submit('Add Resource', array('class' => 'btn')) ?>
    </div>
    <?php echo $this->Form->end() ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Focus the title field.
        $('#ResourceTitle').focus();
        // Autocomplete the tags field.
        arcs.utils.autocomplete({
            sel: '#ResourceTags',
            multiple: true,
            source: arcs.utils.complete.tag()
        });
    });
</script>

<div id="upload-nav" class="well">
    <span class="btn-group" style="bottom:5px;">
        <a class="btn" href="<?php echo $this->Html->url(
            array('controller' => 'uploads', 'action' => 'batch')) ?>">Batch</a>
        <a class="btn active" href="<?php echo $this->Html->url(
            array('controller' => 'resources', 'action' => 'add')) ?>">Standard</a>
    </span>
    &nbsp;
    <span>
        Need some help? See our 
        <?php echo $this->Html->link('Uploading', '/help/uploading') ?> documentation.
    </span>
</div>

<div class="arcs-form" style="width:500px;">
    <?php echo $this->Form->create('Resource', array('type' => 'file')) ?>
    <?php echo $this->Form->input('title', array('type' => 'text')) ?><br>
    <?php echo $this->Form->input('type', array('options' => $types)) ?><br>
    <?php echo $this->Form->input('public', array('label' => 'Public?')) ?><br>
    <?php echo $this->Form->input('exclusive', array('label' => 'Exclusive?')) ?><br>
    <?php echo $this->Form->input('file', array('type' => 'file')) ?><br>
    <?php echo $this->Form->input('keywords', array('type' => 'text', 'style' => 'width:400px')) ?><br>
    <div class="arcs-form-actions">
        <?php echo $this->Form->submit('Upload', array('class' => 'btn success')) ?>
    </div>
    <?php echo $this->Form->end() ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Focus the title field.
        $('#ResourceTitle').focus();
        // Autocomplete the keywords field.
        arcs.utils.autocomplete({
            sel: '#ResourceKeywords',
            multiple: true,
            source: arcs.utils.complete.keyword()
        });
    });
</script>

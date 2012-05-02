<?php echo $this->element('uploads_nav', array('active' => 'basic')) ?>

<div class="arcs-form" style="width:500px;">
    <?php echo $this->Form->create('Resource', array('type' => 'file')) ?>
    <?php echo $this->Form->input('title', array('type' => 'text')) ?><br>
    <?php echo $this->Form->input('type', array('options' => array(
        'Photograph' => 'Photograph', 
        'Notebook' => 'Notebook', 
        'Inventory Card' => 'Inventory Card', 
        'Report' => 'Report', 
        'Drawing' => 'Drawing', 
        'Map' => 'Map'
    ))) ?><br>
    <?php echo $this->Form->input('public', array('label' => 'Public?')) ?><br>
    <?php echo $this->Form->input('file', array('type' => 'file')) ?><br>
    <?php echo $this->Form->input('keywords', array('type' => 'text', 'style' => 'width:400px')) ?><br>
    <div class="arcs-form-actions">
        <?php echo $this->Form->submit('Upload', array('class' => 'btn btn-success')) ?>
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
            source: arcs.complete('keywords/complete')
        });
    });
</script>

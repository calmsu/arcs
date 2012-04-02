<div id="upload-nav" class="well well-nav">
    <span class="btn-group" style="bottom:5px;">
        <a class="btn <?php echo $active == 'batch' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('controller' => 'uploads', 'action' => 'batch')) ?>">Batch</a>
        <a class="btn <?php echo $active == 'basic' ? 'active' : '' ?>" 
            href="<?php echo $this->Html->url(
            array('controller' => 'uploads', 'action' => 'basic')) ?>">Basic</a>
    </span>&nbsp;
    <span>
        Need some help? See our 
        <?php echo $this->Html->link('Uploading', '/help/uploading') ?> documentation.
    </span>
</div>


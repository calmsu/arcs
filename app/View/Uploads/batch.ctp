<div class="btn-group pull-left">
    <a class="btn active" href="<?php echo $this->Html->url(
        array('controller' => 'uploads', 'action' => 'batch')) ?>">Batch</a>
    <a class="btn" href="<?php echo $this->Html->url(
        array('controller' => 'resources', 'action' => 'add')) ?>">Standard</a>
</div>
<div class="pull-left" style="padding:7px; padding-left:20px;">
    Need some help? See our 
    <?php echo $this->Html->link('Uploading', '/docs/uploading') ?> documentation.
</div>

<div id="batch-upload">
    <div class="accordion" id="uploads-container">
        <span id="drop-msg">Drop files here...</span>
    </div>
    <br>
    <div class="controls">
        <button id="upload-btn" class="btn success disabled">Upload &amp; Fill in Metadata</button>
        <div id="fileupload-wrapper" class="btn">
            <span>Add files...</span>
            <input id="fileupload" type="file" name="files[]" multiple>
        </div>
        <span id="progress-all"></span>
    </div>
</div>

<script type="text/javascript">
    arcs.uploadView = new arcs.views.Upload({
        el: $('#batch-upload')
    });
</script>

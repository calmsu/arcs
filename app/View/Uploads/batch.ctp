<?php echo $this->element('uploads_nav', array('active' => 'batch')) ?>

<div id="batch-upload">
    <div class="accordion" id="uploads-container">
        <span id="drop-msg">Drop files here...</span>
    </div>
    <br>
    <div class="controls">
        <button id="upload-btn" class="btn btn-success disabled">Upload &amp; Fill in Metadata</button>
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

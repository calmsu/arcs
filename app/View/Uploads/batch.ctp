<div id="batch-upload">
    <div id="dropzone">
        <div>
            Drag &amp; Drop files here...
            <br><br>
            <input id="fileupload" type="file" name="files[]" multiple>
        </div>
    </div>
    <br>
    <div class="accordion" id="uploads-container"></div>
</div>

<script type="text/javascript">
    arcs.uploadView = new arcs.views.Upload({
        el: $('#batch-upload')
    });
</script>

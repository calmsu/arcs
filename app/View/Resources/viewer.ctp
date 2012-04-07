<div id="viewer" class="row">
    <div id="standalone" class="viewer-well">
        <div id="hotspots-wrapper"></div>
        <div id="resource"></div>
    </div>
    <div class="viewer-tabs tabbable">
        <ul class="nav nav-tabs">
            <li class="active" id="primary">
                <a data-toggle="tab" href="#information">Info</a>
            </li>
            <li id="secondary">
                <a data-toggle="tab" href="#discussion">Discussion</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="information">
                <h3>Resource</h3>
                <table id="resource-details" 
                    class="details table table-striped table-bordered"></table>
                <hr>
                <h3>Collections</h3>
                <div id="memberships-wrapper">
                <?php foreach($memberships as $m): ?>
                    <?php echo $this->Html->link(
                        $m['Collection']['title'],
                        '/collection/' . $m['Collection']['id'] . '/' . $resource['Resource']['id']
                    ) ?>
                    <br>
                <?php endforeach ?>
                </div>
                <hr>
                <h3>Annotations</h3>
                <div id="annotations-wrapper"></div>
                <hr>
                <h3>Keywords</h3>
                <div id="keywords-wrapper"></div>
                <br>
                <input id="keyword-btn" class="unfocused" type="text" placeholder="New keyword..." />
            </div>
            <div class="tab-pane" id="discussion">
                <div id="comment-wrapper"></div>
                <hr>
                <p><textarea id="content" name="content"></textarea></p>
                <input id="comment-btn" type="submit" class="btn" value="Comment" />
            </div>
        </div>
    </div>
</div>

<!-- Give the resource array to the client-side code -->
<script>
  arcs.resource = new arcs.models.Resource(<?php echo json_encode($resource) ?>);
  arcs.collection = new arcs.collections.Collection();
  arcs.viewer = new arcs.views.Viewer({
    model: arcs.resource,
    collection: arcs.collection,
    el: $('#viewer')
  });
</script>

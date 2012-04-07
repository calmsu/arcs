<div id="viewer" class="row">
	<div class="viewer-well">
		<div id="prev-btn" class="viewer-nav"></div>
		<div id="next-btn" class="viewer-nav"></div>
		<div id="wrapping">
            <div id="hotspots-wrapper"></div>
            <div id="resource"></div>
		</div>
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
                <h3>Collection</h3>
                <table id="collection-details" 
                    class="details table table-striped table-bordered"></table>
                <h3>Resource</h3>
                <table id="resource-details" 
                    class="details table table-striped table-bordered"></table>
                <hr>
                <h3>Annotations</h3>
                <div id="annotations-wrapper"></div>
                <hr>
                <h3>Keywords</h3>
                <div id="keywords-wrapper"></div>
                <br>
                <?php if ($user['role'] < 3): ?>
                <input id="keyword-btn" class="unfocused" type="text" placeholder="New keyword..." />
                <?php endif ?>
            </div>
            <div class="tab-pane" id="discussion">
                <div id="comment-wrapper"></div>
                <hr>
                <?php if ($user['role'] < 3): ?>
                <textarea id="content" name="content"></textarea>
                <br><br>
                <input id="comment-btn" type="submit" class="btn" value="Comment" />
                <?php else: ?>
                <?php echo $this->Html->link('Login', '/login') ?> to comment.
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<div id="carousel-wrapper" class="es-carousel-wrapper">
    <div id="carousel" class="es-carousel">
        <ul></ul>
    </div>
</div>

<!-- Bootstrap our backbone models -->
<script>
  arcs.collectionData = <?php echo json_encode($collection) ?>;
  arcs.collection = new arcs.collections.Collection(
    <?php echo json_encode($resources); ?>
  );
  arcs.resource = new arcs.models.Resource(
    <?php echo json_encode($resources[0]) ?>
  );
  arcs.viewer = new arcs.views.Viewer({
    model: arcs.resource,
    collection: arcs.collection,
    el: $('#viewer')
  });
</script>

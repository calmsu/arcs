<div id="resource-wrapper" class="row">
    <div id="prev-button"></div>
    <div id="resource"></div>
    <div id="hotspots-wrapper" style="left:18px"></div>
    <div id="next-button"></div>
	
    <div class="tab-wrapper" id="arcs-tab-wrapper" style="top:-550px">
        <ul class="nav tabs">
            <li class="active" id="primary">
                <a data-toggle="tab" href="#information">Info</a>
            </li>
            <li id="secondary">
                <a data-toggle="tab" href="#discussion">Discussion</a>
            </li>
        </ul><!-- .tab-heads -->
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
                <input id="keyword-btn" class="unfocused" type="text" placeholder="New keyword..." />
            </div>
            <div class="tab-pane" id="discussion">
                <div id="comment-wrapper"></div>
                <hr>
                <textarea id="content" name="content"></textarea>
                <br><br>
                <input id="comment-btn" type="submit" class="btn" value="Comment" />
            </div>
        </div>
    </div>
</div>
<div style="clear:both"></div>
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
    el: $('#resource-wrapper')
  });
</script>

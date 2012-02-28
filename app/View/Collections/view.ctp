<div id="resource-wrapper" class="row">
    
    <div id="prev-button"></div>
    <div id="resource"></div>
    <div id="hotspots-wrapper" style="position:relative; left:18px;"></div>
    <div id="next-button"></div>
    <div class="loading" id="img-loader"></div>
	
    <div class="tab-wrapper" id="arcs-tab-wrapper" style="top:-550px">

        <ul class="nav tabs">
            <li class="active" id="primary"><a data-toggle="tab" href="#information">Info</a></li>
            <li id="secondary"><a data-toggle="tab" href="#discussion">Discussion</a></li>
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
                <h3>Tags</h3>
                <div id="tags-wrapper"></div>
                <br>
                <input id="new-tag" class="unfocused" type="text" placeholder="New tag..." />
            </div><!-- #information.tab-pane -->

            <div class="tab-pane" id="discussion">
                <div id="comment-wrapper"></div>
                <hr>
                <textarea id="content" name="content"></textarea>
                <br><br>
                <input id="comment-button" type="submit" class="btn" value="Comment" />
            </div><!-- tab-pane -->

        </div><!-- sidebar-tab-content -->		    

    </div><!-- tab-wrapper -->
</div><!-- .row -->
<div style="clear:both"></div>
<div id="carousel" class="es-carousel-wrapper">
    <div class="es-carousel">
        <ul>
        <?php for($i=0; $i<count($resources); $i++): ?>
            <li>
                <img class="thumb"
                     src="<?php echo $resources[$i]['thumb'] ?>" 
                     alt="" style="width:100px; height:90px;" 
                     data-id="<?php echo $resources[$i]['id'] ?>" />
                <div class="overlay">
                    <span><?php echo $i + 1 ?></span>
                </div>
            </li>
        <?php endfor ?>
        </ul>
    </div><!-- es-carousel -->
</div><!-- carousel -->

<!-- Bootstrap our backbone models -->
<script>
    arcs.collectionData = <?php echo json_encode($collection) ?>;
    arcs.collection = new arcs.collections.Collection(
        <?php echo json_encode($resources); ?>
    );
    arcs.resource = new arcs.models.Resource(
        <?php echo json_encode($resources[0]) ?>
    );
    arcs.resourceView = new arcs.views.Resource({
        model: arcs.resource,
        collection: arcs.collection,
        el: $('#resource-wrapper')
    });
</script>

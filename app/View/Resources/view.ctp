<style>#resource img {border:1px solid #888;}</style> 

<div id="resource-wrapper" class="row">
    <div id="resource"></div>
    <div id="hotspots-wrapper"></div>
    <div class="tab-wrapper" id="arcs-tab-wrapper" style="top:-550px">

        <ul class="nav tabs">
            <li class="active" id="primary"><a data-toggle="tab" href="#information">Info</a></li>
            <li id="secondary"><a data-toggle="tab" href="#discussion">Discussion</a></li>
        </ul><!-- .tab-heads -->

        <div class="tab-content">
            <div class="tab-pane active" id="information">
                <h3>Resource</h3>
                <table id="resource-details" 
                    class="details table table-striped table-bordered"></table>
                <hr>
                <h3>Memberships</h3>
                <div id="memberships-wrapper">
                <?php foreach($memberships as $m): ?>
                    <?php echo $this->Html->link(
                        # If it doesn't have a title, use the description
                        $m['Collection']['title'] ? $m['Collection']['title']: $m['Collection']['description'],
                        '/collection/' . $m['Collection']['id']) ?>
                    <br>
                <?php endforeach ?>
                </div>
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

<!-- Give the resource array to the client -->
<script>
    arcs.resource = new arcs.models.Resource(<?php echo json_encode($resource) ?>);
    arcs.collection = new arcs.collections.Collection();
    arcs.resourceView = new arcs.views.Resource({
        model: arcs.resource,
        collection: arcs.collection,
        el: $('#resource-wrapper')
    });
</script>

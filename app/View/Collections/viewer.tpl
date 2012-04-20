<div id="viewer-controls">
  {{ html.link(collection.title, '/collection/' ~ collection.id, 
    {'class': 'title subtle'}) }} 
  <input type="text" class="collection-search toolbar-btn" 
    placeholder="Search this collection..." />
  <button id="thumbs-btn" class="btn toolbar-btn" rel="tooltip"
    data-original-title="View this collection in the search">
    <i class="icon-th-large"></i></button>
  <button id="full-screen-btn" class="btn toolbar-btn" rel="tooltip"
    data-original-title="Enter full screen"><i class="icon-resize-full"></i></button>
  <div class="page-nav toolbar-btn input-append input-prepend">
    <button id="mini-prev-btn" class="btn"><i class="icon-arrow-left"></i></button>
    <input type="text" class="span2" />
    <button id="mini-next-btn" class="btn"><i class="icon-arrow-right"></i></button>
  </div>
  <div id="export-buttons" class="btn-group toolbar-btn">
    <button id="export-btn" class="btn dropdown-toggle" rel="tooltip"
      data-toggle="dropdown" data-original-title="Export this resource">
      <i class="icon-download-alt"></i> Export
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a id="download-btn">Download</a></li>
    </ul>
  </div>
  {% if user.loggedIn %}
  <div id="action-buttons" class="btn-group toolbar-btn">
    <button id="edit-btn" class="btn" rel="tooltip" 
      data-original-title="Edit this resource's info"><i class="icon-pencil"></i> Edit</button>
    <button id="flag-btn" class="btn" rel="tooltip"
      data-original-title="Flag this resource"><i class="icon-flag"></i> Flag</button>
    <div id="advanced-buttons" class="btn-group pull-left">
      <button id="advanced-btn" class="btn" rel="tooltip" style="border-left:none"
        data-toggle="dropdown"><i class="icon-cog"></i>
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a id="rethumb-btn">Re-thumbnail</a></li>
        <li><a id="split-btn">Split PDF</a></li>
        {% if user.role == 0 %}
        <li><a id="delete-btn">Delete</a></li>
        {% endif %}
      </ul>
    </div>
  </div>
  {% endif %}
</div>

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
    <ul class="nav nav-pills">
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
        {% if user.loggedIn %}
        <input id="keyword-btn" class="unfocused" type="text" placeholder="New keyword..." />
        {% endif %}
      </div>
      <div class="tab-pane" id="discussion">
        <div id="comment-wrapper"></div>
        <br>
        {% if user.loggedIn %}
        <div class="well">
          <textarea id="content" name="content"></textarea>
          <button id="comment-btn" class="btn">Comment</button>
        </div>
        {% else %}
        {{ html.link('Login', '/login') }} to comment.
        {% endif %}
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
  arcs.collectionModel = new arcs.models.Collection({{ collection|json_encode }});
  arcs.collection = new arcs.collections.Collection({{ resources|json_encode }});
  arcs.resource = new arcs.models.Resource({{ resources[0]|json_encode }});
  arcs.viewer = new arcs.views.Viewer({
    model: arcs.resource,
    collection: arcs.collection,
    collectionModel: arcs.collectionModel,
    el: $('#viewer')
  });
</script>

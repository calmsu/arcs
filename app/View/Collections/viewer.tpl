<div id="viewer-controls">
  {{ html.link(collection.title, '/collection/' ~ collection.id, 
    {'class': 'title subtle'}) }} 
  <input type="text" class="collection-search toolbar-btn" 
    placeholder="Search this collection..." />
  <button id="thumbs-btn" class="btn toolbar-btn" rel="tooltip"
    title="View this collection in the search" data-placement="bottom">
    <i class="icon-th-large"></i></button>
  <button id="full-screen-btn" class="btn toolbar-btn" rel="tooltip" title="Fullscreen"
    data-placement="bottom"><i class="icon-resize-full"></i></button>
  <button id="annotation-vis-btn" class="btn toolbar-btn" rel="tooltip" 
    data-placement="bottom"><i class="icon-map-marker"></i></button>
  <div id="zoom-buttons" class="btn-group toolbar-btn">
    <button id="zoom-in-btn" class="btn"><i class="icon-zoom-in"></i></button>
    <button id="zoom-out-btn" class="btn disabled"><i class="icon-zoom-out"></i></button>
  </div>
  <div class="page-nav toolbar-btn input-append input-prepend">
    <button id="mini-prev-btn" class="btn"><i class="icon-arrow-left"></i></button>
    <input type="text" class="span2" />
    <button id="mini-next-btn" class="btn"><i class="icon-arrow-right"></i></button>
  </div>
  <div id="export-buttons" class="btn-group toolbar-btn">
    <button id="export-btn" class="btn dropdown-toggle" data-toggle="dropdown">
      <i class="icon-download-alt"></i> Export
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a id="download-btn">Download</a></li>
    </ul>
  </div>
  {% if user.loggedIn %}
  <div id="action-buttons" class="btn-group toolbar-btn">
    <button id="annotate-btn" class="btn" rel="tooltip" title="Annotate this resource"
      data-placement="bottom"><i class="icon-screenshot"></i> Annotate</button>
    <button id="edit-btn" class="btn" rel="tooltip" title="Edit this resource's info"
      data-placement="bottom"><i class="icon-pencil"></i> Edit</button>
    <button id="flag-btn" class="btn" rel="tooltip"
      title="Flag this resource" data-placement="bottom"><i class="icon-flag"></i> Flag</button>
    <div id="advanced-buttons" class="btn-group pull-left">
      <button id="advanced-btn" class="btn" style="border-left:none" data-toggle="dropdown">
        <i class="icon-cog"></i>
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a id="rethumb-btn">Re-thumbnail</a></li>
        <li><a id="split-btn">Split PDF</a></li>
        {% if user.role == 0 %}
        <li class="divider"></li>
        <li><a id="delete-btn">Delete this resource...</a></li>
        <li><a id="delete-col-btn">Delete collection...</a></li>
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
      <div class="annotate-controls">
        <span>Click and drag to annotate</span>
        <button id="annotate-done-btn">
          <i class="icon-white icon-ok"></i> Done
        </button>
        <button id="annotate-new-btn">
          <i class="icon-white icon-map-marker"></i> New Annotation
        </button>
      </div>
    </div>
  </div>
  <div class="viewer-tabs tabbable">
    <ul class="nav nav-pills">
      <li class="active" id="information-btn">
        <a data-toggle="tab" href="#information">Info</a>
      </li>
      <li id="notations-btn">
        <a data-toggle="tab" href="#notations">Notations</a>
      </li>
      <li id="discussion-btn">
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
      </div>
      <div class="tab-pane" id="notations">
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

<div id="viewer-controls">
  {{ html.link(resource.Resource.title, '/resource/' ~ resource.Resource.id, 
    {'class': 'title subtle'}) }} 
  <button id="full-screen-btn" class="btn toolbar-btn" rel="tooltip"
    data-original-title="Enter full screen"><i class="icon-resize-full"></i></button>
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
  <div id="standalone" class="viewer-well">
    <div id="hotspots-wrapper"></div>
    <div id="resource"></div>
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
        <h3>Resource</h3>
        <table id="resource-details" 
            class="details table table-striped table-bordered"></table>
        <hr>
        <h3>Collections</h3>
        <div id="memberships-wrapper">
        {% for m in memberships %}
          {{ html.link(m.Collection.title, 
            '/collection/' ~ m.Collection.id ~ '/' ~ resource.Resource.id) }}
          <br>
        {% endfor %}
        </div>
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
        <textarea id="content" name="content"></textarea>
        <button id="comment-btn" class="btn">Comment</button>
        {% else %}
        {{ html.link('Login', '/login') }} to comment.
        {% endif %}
      </div>
    </div>
  </div>
</div>

<div class="viewer-footer"></div>

<!-- Give the resource array to the client-side code -->
<script>
  arcs.resource = new arcs.models.Resource({{ resource|json_encode }});
  arcs.collection = new arcs.collections.Collection();
  arcs.viewer = new arcs.views.Viewer({
    model: arcs.resource,
    collection: arcs.collection,
    el: $('#viewer')
  });
</script>

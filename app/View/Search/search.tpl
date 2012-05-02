<div class="search-wrapper"></div>
<div id="search-results-wrapper">
  <div id="search-actions" class="search-toolbar">
    <div id="action-buttons" class="btn-group">
    {% if user.loggedIn %}
      <div id="collection-buttons" class="btn-group pull-left">
        <button id="test-btn" class="btn no-rounded needs-resource disabled"
           data-toggle="dropdown"><i class="icon-book"></i> Collection
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          <li><a id="collection-btn">Create new collection...</a></li>
          <li><a id="collection-add-btn">Add to existing collection...</a></li>
          <li><a id="bookmark-btn">Add to bookmarks</a></li>
        </ul>
      </div>
      <button id="keyword-btn" class="btn needs-resource disabled" rel="tooltip"
        title="Keyword the selected results" data-placement="bottom">
        <i class="icon-tag"></i> Keyword
      </button>
      <button id="attribute-btn" class="btn needs-resource no-rounded disabled" 
        rel="tooltip" title="Edit the attributes of the selected results" 
        data-placement="bottom">
        <i class="icon-pencil"></i> Edit
      </button>
      <button id="flag-btn" class="btn needs-resource disabled" rel="tooltip"
        title="Flag the selected results" data-placement="bottom">
        <i class="icon-flag"></i> Flag
      </button>
      <div id="advanced-buttons" class="btn-group pull-left">
        <button id="advanced-btn" class="btn needs-resource no-rounded disabled" 
          rel="tooltip" style="border-left:none" data-toggle="dropdown">
          <i class="icon-cog"></i>
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
          {% if user.role < 2 %}
          <li><a id="access-btn">Set Access...</a></li>
          <li class="divider"></li>
          {% endif %}
          <li><a id="rethumb-btn">Redo thumbnail</a></li>
          <li><a id="repreview-btn">Redo preview</a></li>
          <li><a id="split-btn">Split PDF</a></li>
          {% if user.role == 0 %}
          <li class="divider"></li>
          <li><a id="delete-btn">Delete...</a></li>
          <li class="divider"></li>
          <li><a id="solr-btn">Queue SOLR index</a></li>
          {% endif %}
        </ul>
      </div>
    {% else %}
      <div style="height:28px"></div>
    {% endif %}
    </div>
    <div id="view-buttons" class="btn-group actions-right">
      <button id="grid-btn" class="btn active">
        <i class="icon-th-large"></i> Grid
      </button>
      <button id="list-btn" class="btn">
        <i class="icon-th-list"></i> List
      </button>
    </div>
    <div id="sort-buttons" class="btn-group actions-right">
      <button id="sort-btn" class="btn dropdown-toggle" data-toggle="dropdown">
        Sort by <span id="sort-by">modified</span>
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a class="sort-btn" id="sort-title-btn">title&nbsp;</a></li>
        <li><a class="sort-btn" id="sort-modified-btn">modified&nbsp;
          <i class="icon-ok"></i></a></li>
        <li><a class="sort-btn" id="sort-created-btn">created&nbsp;</a></li>
        <li class="divider"></li>
        <li><a class="dir-btn" id="dir-asc-btn">ascending&nbsp;</a></li>
        <li><a class="dir-btn" id="dir-desc-btn">descending&nbsp;
          <i class="icon-ok"></i></a></li>
      </ul>
    </div>
    <div id="open-buttons" class="btn-group actions-right">
      <button id="open-btn" class="btn needs-resource disabled" rel="tooltip"
        title="Open selected results" data-placement="bottom">Open</button>
      <button class="btn needs-resource disabled dropdown-toggle" 
        data-toggle="dropdown">
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a id="open-btn">In separate windows</a></li>
        <li><a id="open-colview-btn">In a collection view</a></li>
      </ul>
    </div>
    <div id="export-buttons" class="btn-group actions-right" style="margin-right:30px">
      <button id="export-btn" class="btn dropdown-toggle needs-resource disabled" 
        data-toggle="dropdown">
        <i class="icon-download-alt"></i> Export
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a id="download-btn">Download</a></li>
        <li><a id="zipped-btn">Download as zipfile</a></li>
      </ul>
    </div>
    <button id="top-btn" class="btn actions-right" style="margin-right:10px; display:none;">
      <i class="icon-arrow-up"></i> Back to Top
    </button>
  </div>
  <div id="search-results"></div>
</div>

<script>
  arcs.searchView = new arcs.views.search.Search({
    el: $('#search-results-wrapper')
  });
</script>

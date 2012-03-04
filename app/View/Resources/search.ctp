<br>
<form>
    <div id="search-wrapper"></div>
</form>
<div id="search-notify" class="alert alert-info">
    Content
</div>
<div id="search-results-wrapper">
    <div id="search-actions" class="mini-toolbar">
        <div id="action-buttons" class="btn-group">
        <?php if ($user['loggedIn']): ?>
            <button id="bookmark-btn" class="btn" rel="tooltip"
                data-original-title="Bookmark the selected results">
                <i class="icon-bookmark"></i> Bookmark
            </button>
            <button id="tag-btn" class="btn" rel="tooltip"
                data-original-title="Tag the selected results">
                <i class="icon-tag"></i> Tag
            </button>
            <button id="collection-btn" class="btn" rel="tooltip"
                data-original-title="Create or add to a collection from selected">
                <i class="icon-book"></i> Collection
            </button>
            <button id="attribute-btn" class="btn" rel="tooltip"
                data-original-title="Edit the attributes of the selected results">
                <i class="icon-pencil"></i> Attribute
            </button>
        <?php else: // placeholder ?>
            <div style="height:28px"></div>
        <?php endif ?>
        </div>
        <div id="view-buttons" class="btn-group">
            <button id="grid-btn" class="btn active">
                <i class="icon-th-large"></i> Grid
            </button>
            <button id="list-btn" class="btn">
                <i class="icon-th-list"></i> List
            </button>
        </div>
        <div id="open-buttons" class="btn-group" 
            style="float:right; right:20px; bottom:28px;">
            <button id="open-btn" class="btn" rel="tooltip"
                data-original-title="Open selected results">Open</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a id="open-btn" style="cursor:pointer">
                    In separate windows</a></li>
                <li><a id="open-colview-btn" style="cursor:pointer">
                    In a collection view</a></li>
            </ul>
        </div>
    </div>
    <div id="search-results"></div>
</div>

<script type="text/javascript">
    arcs.searchView = new arcs.views.Search({
        el: $('#search-results-wrapper')
    });
</script>

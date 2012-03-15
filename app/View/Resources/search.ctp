<div id="search-wrapper"></div>
<div id="search-results-wrapper">
    <div id="search-actions" class="search-toolbar">
        <div id="action-buttons" class="btn-group">
        <?php if ($user['loggedIn']): ?>
            <button id="bookmark-btn" class="btn" rel="tooltip"
                data-original-title="Bookmark the selected results">
                <i class="icon-bookmark"></i> Bookmark
            </button>
            <button id="keyword-btn" class="btn" rel="tooltip"
                data-original-title="Keyword the selected results">
                <i class="icon-tag"></i> Keyword
            </button>
            <button id="collection-btn" class="btn" rel="tooltip"
                data-original-title="Create or add to a collection from selected">
                <i class="icon-book"></i> Collection
            </button>
            <button id="attribute-btn" class="btn" rel="tooltip"
                data-original-title="Edit the attributes of the selected results">
                <i class="icon-pencil"></i> Attribute
            </button>
            <button id="flag-btn" class="btn" rel="tooltip"
                data-original-title="Flag the selected results">
                <i class="icon-flag"></i> Flag
            </button>
        <?php else: // placeholder ?>
            <div style="height:28px"></div>
        <?php endif ?>
        </div>
        <div id="view-buttons" class="btn-group actions-right">
            <button id="grid-btn" class="btn active">
                <i class="icon-th-large"></i> Grid
            </button>
            <button id="list-btn" class="btn">
                <i class="icon-th-list"></i> List
            </button>
        </div>
        <!-- Very soon...
        <div id="sort-buttons" class="btn-group actions-right">
            <button id="sort-btn" class="btn" data-toggle="dropdown">
                Sort by <span id="sort-by">modified</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a id="sort-relevance-btn">relevance</a></li>
                <li><a id="open-name-btn">name</a></li>
            </ul>
        </div>
        -->
        <div id="open-buttons" class="btn-group actions-right">
            <button id="open-btn" class="btn" rel="tooltip"
                data-original-title="Open selected results">Open</button>
            <button class="btn dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <li><a id="open-btn">In separate windows</a></li>
                <li><a id="open-colview-btn">In a collection view</a></li>
            </ul>
        </div>
        <button id="top-btn" class="btn actions-right" style="margin-right:40px; display:none">
            <i class="icon-arrow-up"></i> Back to Top
        </button>
    </div>
    <div id="search-results"></div>
</div>

<script type="text/javascript">
    arcs.searchView = new arcs.views.Search({
        el: $('#search-results-wrapper')
    });
</script>

# templates.coffee
# ----------------
# Mustache templates for various things.

arcs.templates.resourceImage = 
    """
    <img src="{{ url }}" alt="resource" data-id="{{ id }}">
    """

arcs.templates.resourceDocument = 
    """
    <iframe src="http://docs.google.com/gview?url={{ url }}&embedded=true"
        style="width:60%; height:100%; float:left;" frameborder="0"></iframe>
    """

arcs.templates.resourceTable = 
    """
    <tr>
        <td>Title</td>
        <td>{{ title }}
    </tr>
    <tr>
        <td>Public</td>
        {{# public }}
        <td>Yes</td>
        {{/ public }}
        {{^ public }}
        <td>No</td>
        {{/ public }}
    </tr>
    <tr>
        <td>Created</td>
        <td>{{ created }}</td>
    </tr>
    {{# modified }}
    <tr>
        <td>Modified</td>
        <td>{{ modified }}</td>
    </tr>
    {{/ modified }}
    <tr>
        <td>Download Link</td>
        <td><a href="{{ url }}">{{ file_name }}</a></td>
    </tr>
    """

arcs.templates.collectionTable =
    """
    <tr>
        <td>Title</td>
        <td>{{ title }}
    </tr>
    <tr>
        <td>Description</td>
        <td>{{ description }}
    </tr>
    <tr>
        <td>Public</td>
        {{# public }}
        <td>Yes</td>
        {{/ public }}
        {{^ public }}
        <td>No</td>
        {{/ public }}
    </tr>
    {{# pdf }}
    <tr>
        <td>Original PDF</td>
        <td><a href="../resource/{{ pdf }}">Link</a></td>
    </tr>
    {{/ pdf }}
    """

arcs.templates.discussion = 
    """
    {{# comments }}
    <div class="comment-wrapper" id="comment-{{ id }}">
        <div class="comment-header">
            <span class="name">{{ name }}{{ _name }}</span>
            commented 
            <span class="time">{{ created }}{{ _created }}</span>
        </div>
        <div class="comment">{{ content }}</div>
    </div>
    {{/ comments }}
    """

arcs.templates.tagList =
    """
    {{# tags }}
    <a class="tag" id="tag-{{ id }}">
        {{ tag }}
    </a>
    <br>
    {{/ tags }}
    """

arcs.templates.hotspotModal =
    """
    <div class="modal-header">
        <h3>New Annotation</h3>
        <span id="drag-handle" style="float:right; position:relative; top:-20px;">
            <i class="icon-move"></i>
        </span>
    </div>
    <div class="modal-body">
        <h4>Type</h4>
        <select id="type">
            <option value="photo">Photo</option>
            <option value="sketch">Sketch</option>
            <option value="report">Report</option>
        </select>
        <h4>Title</h4>
        <input type="text" id="title" />
        <h4>Caption</h4>
        <textarea id="caption"></textarea>
        <hr>
        <div class="tabbable">
            <ul class="nav tabs">
                <li class="active"><a href="#resource-link" data-toggle="tab">Resource</a></li>
                <li><a href="#url-link" data-toggle="tab">URL</a></li>
            </ul>
            <div class="tab-content">
                <div id="resource-link" class="tab-pane active" style="height:200px">
                    <div id="hotspot-search"></div>
                    <div id="hotspot-search-results" style="height:170px; overflow:auto;"></div>
                </div>
                <div id="url-link" class="tab-pane" style="height:45px">
                    <div class="input-prepend">
                        <span class="add-on">http://</span>
                        <input id="url" type="text" style="width:85%" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button id="cancel" class="btn">Cancel</button>
        <button id="save" class="btn success">Save</button>
    </div>
    """

arcs.templates.hotspot = 
    """
    {{# hotspots }}
    <a class="hotspot" style="left:{{left}}px; top:{{top}}px; width:{{width}}px;
        height:{{height}}px;" rel="popover" data-original-title="{{type}}"
        data-content="{{caption}}" href="{{link}}"></a>
    {{/ hotspots }}
    """

arcs.templates.annotation = 
    """
    {{# hotspots }}
    <a class="annotation">{{ type }}</a>
    <br>
    <span class="annotation-caption">{{ caption}}</span>
    <br>
    {{/ hotspots }}
    """

arcs.templates.button = 
    """
    <a class="btn icon unselectable" id="{{ id }}"
        {{# url }} href="{{ url }}" {{/ url }}>
        <span class="{{ class }}"></span>
        {{ text }}
    </a>
    """

arcs.templates.resultsGrid = 
    """
    {{# results }}
    <div class="result grid">
        <img src="{{ thumb }}" data-id="{{ id }}" style="height:100px" />
        <div><strong>{{ title }}</strong></div>
        <div>{{ user_name }}</div>
    </div>
    {{/ results }}
    {{^ results }}
    <div id="no-results">No Results</div>
    {{/ results }}
    """

arcs.templates.resultsList = 
    """
    <table>
    {{# results }}
    <tr class="result list">
        <td>
            <img src="{{ thumb }}" data-id="{{ id }}" style="width:100px" />
        </td>
        <td>
            <div><strong>{{ title }}</strong></div>
            <br>
            <div>{{ user_name }}</div>
        </td>
    </tr>
    {{/ results }}
    </table>
    {{^ results }}
    <div id="no-results">No Results</div>
    {{/ results }}
    """

arcs.templates.modalWrapper =
    """
    <div id="modal" class="modal" style="display:none; max-height:none;"></div>
    """

arcs.templates.searchModal = 
    """
    <div class="modal-header">
        <h3>{{ title }}</h3>
    </div>
    <div class="modal-body">
        {{ message }}
        <br><br>
        <input type="text" value="{{ value }}" id="search-modal-value" />
    </div>
    <div class="modal-footer">
        <button id="cancel" class="btn">Cancel</button>
        <button id="save" class="btn info">Make it so</button>
    </div>
    """

arcs.templates.splitModal =
    """
    <div class="modal-header">
        <h3>PDF</h3>
    </div>
    <div class="modal-body">
        We noticed you've uploaded a PDF. If you'd like, we can
        split the PDF into a collection, where it can be annotated
        and commented on--page by page.
        <hr>
        <h4>Make a collection from this resource?</h4>
    </div>
    <div class="modal-footer">
        <button id="cancel" class="btn">No, leave it alone.</button>
        <button id="yes" class="btn success">Yes</button>
    </div>
    """

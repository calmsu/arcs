<?php
// Development javascripts. In production, these are minified and 
// concatenated into arcs.min.js.
if ($dev == true) {
    echo $this->Html->script(array(
        # jQuery
        'vendor/jquery.min',
        # Underscore
        'vendor/underscore-min',
        # Backbone
        'vendor/backbone-0.5.0',
        # Mustache
        'vendor/mustache',
        # jQuery UI & friends
        'vendor/jquery.ui.core',
        'vendor/jquery.ui.widget',
        'vendor/jquery.ui.mouse',
        'vendor/jquery.ui.position',
        'vendor/jquery.ui.draggable',
        'vendor/jquery.ui.autocomplete',
        'vendor/jquery.ui.selectable',
        # Bootstrap
        'vendor/bootstrap-modal',
        'vendor/bootstrap-alert',
        'vendor/bootstrap-twipsy',
        'vendor/bootstrap-tooltip',
        'vendor/bootstrap-popover',
        'vendor/bootstrap-dropdown',
        'vendor/bootstrap-tab',
        # Elastislide
        'vendor/jquery.elastislide',
        # Utils
        'vendor/visualsearch.js',
        'vendor/relative-date',
        'vendor/jquery.imgareaselect.min',
        # ARCS
        'app',
        'dev',
        'utils',
        'utils/mime',
        'utils/keys',
        'utils/modal',
        'utils/hash',
        'utils/completion',
        'utils/search',
        'templates',
        'models/resource',
        'models/comment',
        'models/tag',
        'models/bookmark',
        'models/hotspot',
        'collections/collection',
        'collections/discussion',
        'collections/tag_list',
        'collections/hotspot_map',
        'collections/result_set',
        'views/resource',
        'views/discussion',
        'views/tag',
        'views/hotspot',
        'views/toolbar',
        'views/search'
    ));
} else {
    echo $this->Html->script('arcs.min');
}

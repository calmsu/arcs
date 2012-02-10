<?php
// Development stylesheets. In production, these are minified and
// concatenated into arcs.min.css
if ($dev == true) {
    echo $this->Html->css(array(
        'vendor/imgareaselect-animated',
        'vendor/visualsearch-datauri',
        'vendor/elastislide',
        'vendor/jquery-ui',
        'arcs-skin'
    ));
} else {
    echo $this->Html->css('arcs.min');
}

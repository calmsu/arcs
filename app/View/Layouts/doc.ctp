<!doctype html>
<html>
    <head>
        <title><?php echo $title_for_layout; ?> - ARCS</title>
        <link rel="shortcut icon" 
            href="<?php echo $this->Html->url('/favicon.ico') ?>" 
            type="image/x-icon" />
        <meta name="language" http-equiv="language" content="english" />
        <!-- ios devices go full screen! -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <script>window.CAKE_DEBUG = <?php echo Configure::read('debug') ?>;</script>
        <?php 
        echo $this->Assets->stylesheets();
        echo $this->Html->css('docs');
        echo $this->Assets->scripts();
        ?>
    </head>
    <body>
        <div class="wrap">
            <div class="page doc-page fluid-container">
                <?php echo $this->element('toolbar', $toolbar) ?>
                <?php echo $this->Session->flash() ?>
                <?php echo $this->Session->flash('auth') ?>
                <div class="row">
                    <div class="span3">
                        <?php echo $this->element('doc_sidebar', 
                            compact($sidebar, $active)) ?>
                    </div>
                    <div class="span9 doc">
                        <?php echo $this->fetch('content'); ?>
                    </div>
                </div>
            </div>
            <div class="push"></div>
        </div>
        <?php if ($footer) echo $this->element('footer') ?>
        <script>
            // Quick hack to add paragraph markers to doc headings.
            $(function() {
                $('h2,h3,h4,h5').hover(function() {
                    var html = $(this).html(),
                        para = " <a href='#" + this.id + "' class='para'>&para;</a>";
                    $(this).data('original', html);
                    $(this).html(html + para);
                }, function() {
                    $(this).html($(this).data('original'));
                });
            });
        </script>
    </body>
</html>

<!doctype html>
<html lang="en">
    <head>
      <meta charset="utf-8">
        <title><?php echo $title_for_layout; ?></title>
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
        echo $this->Assets->scripts();
        ?>
        <script>arcs.user = new arcs.models.User(<?php echo json_encode($user) ?>);</script>
    </head>
    <body class="<?php echo $body_class ?>">
        <div class="wrap">
            <div class="page fluid-container">
            <?php 
                if (@$toolbar) 
                    echo $this->element('toolbar', $toolbar);
                echo $this->Session->flash();
                echo $this->Session->flash('auth');
                echo $this->fetch('content'); 
            ?>
            </div>
            <?php if ($footer): ?> 
                <div class="push"></div>
            <?php endif ?>
        </div>
        <?php if ($footer) echo $this->element('footer') ?>
        <?php if ($user['role'] == 0 && Configure::read('debug') == 2) echo $this->element('sql') ?> 
        <!-- user voice -->
        <script type="text/javascript">
            var uvOptions = {};
            (function() {
                var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
                uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/B3bdw2BnP1GAnvQETNhAKA.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
            })();
        </script>
    </body>
</html>

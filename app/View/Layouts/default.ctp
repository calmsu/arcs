<?php #ARCS DEFAULT LAYOUT ?>
<!doctype html>
<html>
    <head>
        <title><?php echo $title_for_layout; ?></title>
        <link rel="shortcut icon" 
            href="<?php echo $this->Html->url('/favicon.ico') ?>" 
            type="image/x-icon" />
        <meta name="language" http-equiv="language" content="english" />
        <!-- ios devices go full screen! -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <?php 
        echo $this->Assets->stylesheets();
        echo $this->Assets->scripts();
        ?>
    </head>
    <body>
        <div class="page fluid-container">
            <?php echo $this->element('toolbar', $toolbar) ?>
            <?php echo $this->Session->flash() ?>
            <?php echo $this->Session->flash('auth') ?>
            <?php echo $this->fetch('content'); ?>
        </div>
    </body>
</html>

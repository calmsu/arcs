<div id="toolbar" class="row">
	<?php if ($logo): ?>
        <?php $text = is_string($logo) ? $logo : "ARCS" ?>
        <a id="logo-wrapper" href="<?php echo $this->Html->url('/') ?>">
            <h1 id="logo"><?php echo $text ?></h1>
        </a>
	<?php endif ?>
    <?php if ($user['loggedIn']): ?>
    <div class="btn-group" style="float:right; top:5px;">
        <button class="btn">
            <i class="icon-user"></i> <?php echo $user['name'] ?> 
        </button>
        <button class="btn dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>
            <?php echo $this->Html->link('Account', '/user/' . $user['username']) ?>
            </li>
            <li>
            <?php echo $this->Html->link('Bookmarks', '/users/bookmarks/' . $user['username']) ?>
            </li>
            <li><?php echo $this->Html->link('Logout', '/logout') ?></li>
        </ul>
    </div>
    <a class="btn info" style="float:right; margin-top:5px; margin-right:10px;"
        href="<?php echo $this->Html->url('/upload')?>">
        <i class="icon-white icon-upload"></i> Upload
    </a>
    <?php else: ?>
    <div class="btn-group" style="float:right; top:5px;">
        <a class="btn" 
            href="<?php echo $this->Html->url('/login') ?>">Login</a>
        <a class="btn success" 
            href="<?php echo $this->Html->url('/signup') ?>">Signup</a>
    </div>
    
    <?php endif ?>
</div><!-- #toolbar -->

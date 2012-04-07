<div id="toolbar" class="row">
	<?php if (!isset($logo) || $logo): ?>
        <a id="logo-wrapper" href="<?php echo $this->Html->url('/') ?>">
            <h1 id="logo">
                <?php echo isset($logo) && is_string($logo) ? $logo : "ARCS" ?>
            </h1>
        </a>
	<?php endif ?>
    <?php if ($user['loggedIn']): ?>
    <div class="btn-group toolbar-btn">
        <a class="btn" href="<?php echo $this->Html->url('/user/' . $user['username']) ?>">
            <i class="icon-user"></i> <?php echo $user['name'] ?> 
        </a>
        <button class="btn dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><?php echo $this->Html->link('Account', 
                '/user/' . $user['username']) ?></li>
            <li><?php echo $this->Html->link('Bookmarks', 
                '/users/bookmarks/' . $user['username']) ?></li>
            <li><?php echo $this->Html->link('Logout', 
                '/logout') ?></li>
        </ul>
    </div>
    <a class="btn btn-primary toolbar-btn"
        href="<?php echo $this->Html->url('/upload')?>">
        <i class="icon-white icon-upload"></i> Upload
    </a>
    <?php else: ?>
    <div class="btn-group toolbar-btn">
        <a class="btn" 
            href="<?php echo $this->Html->url('/login') ?>">Login</a>
        <a class="btn primary" 
            href="<?php echo $this->Html->url('/signup') ?>">Signup</a>
    </div>
    <?php endif ?>
    <a class="btn btn-info toolbar-btn"
        href="<?php echo $this->Html->url('/help')?>">
        <i class="icon-white icon-file"></i> Help
    </a>
    <?php if ($user['role'] === 0): ?>
    <a class="btn btn-danger toolbar-btn"
        href="<?php echo $this->Html->url('/admin')?>">
        <i class="icon-white icon-lock"></i> Admin
    </a>
    <?php endif ?>
    <?php if (isset($actions) && $actions): ?>
    <div class="btn-group toolbar-btn">
        <button class="btn dropdown-toggle" data-toggle="dropdown">
            <i class="icon-list-alt"></i> Actions <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a>Delete</a></li>
            <li><a>Split PDF</a></li>
        </ul>
    </div>
    <?php endif ?>
</div>

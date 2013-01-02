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
            <img src="http://gravatar.com/avatar/<?php echo $user['gravatar'] ?>?s=15"/>
            <?php echo $user['name'] ?> 
        </a>
        <button class="btn dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><?php echo $this->Html->link('Account', 
                '/user/' . $user['username']) ?></li>
            <li><?php echo $this->Html->link('Logout', 
                '/logout') ?></li>
        </ul>
    </div>
    <?php else: ?>
    <a class="btn toolbar-btn" 
        href="<?php echo $this->Html->url('/login') ?>">Login</a>
    <?php endif ?>
    <div class="btn-group toolbar-btn">
        <?php if ($user['role'] === 0): ?>
        <a class="btn btn-dark"
            href="<?php echo $this->Html->url('/admin')?>">
            <i class="icon-white icon-lock"></i> Admin
        </a>
        <?php endif ?>
        <?php if ($user['loggedIn']): ?>
        <a class="btn btn-dark"
            href="<?php echo $this->Html->url('/upload')?>">
            <i class="icon-white icon-upload"></i> Upload
        </a>
        <?php endif ?>
        <a class="btn btn-dark"
            href="<?php echo $this->Html->url('/collections')?>">
            <i class="icon-white icon-folder-open"></i> Collections
        </a>
        <a class="btn btn-dark"
            href="<?php echo $this->Html->url('/search')?>">
            <i class="icon-white icon-search"></i> Search
        </a>
        <a class="btn btn-dark"
            href="<?php echo $this->Html->url('/help/')?>">
            <i class="icon-white icon-book"></i> Help
        </a>
    </div>
</div>

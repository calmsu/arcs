<div class="well doc-well">
    <ul class="nav nav-list doc-nav">
    <?php foreach ($docs as $k => $v): ?>
        <?php if (is_null($v)): ?>
        <li class="nav-header"><?php echo $k ?></li>
        <?php else: ?>
        <li class="<?php echo ($v == $active ? "active" : '') ?>">
            <?php echo $this->Html->link($k, '/help/' . $v) ?>
        </li>
        <?php endif ?>
    <?php endforeach ?>
    </ul>
</div>

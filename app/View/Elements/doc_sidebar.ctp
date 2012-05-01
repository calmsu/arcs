<div class="well doc-well">
    <ul class="nav nav-list doc-nav">
    <?php foreach ($sidebar as $section => $items): ?>
        <li class="nav-header"><?php echo $section ?></li>
        <?php foreach ($items as $item => $options): ?>
        <?php if (is_array($options)): ?>
        <li class="<?php echo ($options['link'] == $active ? "active" : '') ?>">
            <?php echo $this->Html->link($item, '/help/' . $options['link']) ?>
            <?php if (isset($options['nav']) && $options['link'] == $active): ?>
            <ul class="sub-nav">
            <?php foreach($options['nav'] as $name => $id): ?>
                <li><?php echo $this->Html->link($name, '/help/' . $options['link'] . $id) ?></li>
            <?php endforeach ?>
            </ul>
            <?php endif ?>
        </li>
        <?php else: ?>
        <li class="<?php echo ($options == $active ? "active" : '') ?>">
            <?php echo $this->Html->link($item, '/help/' . $options) ?>
        </li>
        <?php endif ?>
        <?php endforeach ?>
    <?php endforeach ?>
    </ul>
</div>

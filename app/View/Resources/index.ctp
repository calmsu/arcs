<div id="search-results">
<?php foreach($resources as $r): ?>
    <a href="<?php echo $this->Html->url(
        array('action' => 'view', $r['Resource']['id'])) ?>">
        <img src="<?php echo $r['Resource']['thumb'] ?>">
    </a>
<?php endforeach ?>
</div>

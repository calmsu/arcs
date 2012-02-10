<h3>Bookmarks</h3>
<ol>
<?php foreach($bookmarks as $b): ?>
    <li>
    <?php echo $this->Html->link(array(
        'controller' => 'resources', 
        'action' => 'view', 
        $b['Bookmark']['resource_id'])) 
    ?>
    </li>
<?php endforeach ?>
</ol>

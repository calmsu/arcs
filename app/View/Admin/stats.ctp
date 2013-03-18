<?php echo $this->element('admin_nav', array('active' => 'stats')) ?>

<div class="stats well">
    <ul>
        <li><span class="stat-figure"><?php echo $user_count ?></span> users</li>
        <li><span class="stat-figure"><?php echo $resource_count ?></span> resources</li>
        <li><span class="stat-figure"><?php echo $collection_count ?></span> collections</li>
        <li><span class="stat-figure"><?php echo $metadatum_count ?></span> metadata</li>
        <li><span class="stat-figure"><?php echo $comment_count ?></span> comments</li>
        <li><span class="stat-figure"><?php echo $annotation_count ?></span> annotations</li>
        <li><span class="stat-figure"><?php echo $keyword_count ?></span> keywords</li>
        <li><span class="stat-figure"><?php echo $flag_count ?></span> flags</li>
    </ul>
</div>

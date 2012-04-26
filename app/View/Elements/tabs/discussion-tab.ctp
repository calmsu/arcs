<div class="tab-pane" id="disucssion-tab">
<?php if(empty($user_info['Comment'])): ?>
    <h4>No discussion items</h4>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Content</th>
                <th>For</th>
                <th>Author</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($user_info['Comment'] as $comment): ?>
            <tr>
                <td><?php echo $comment['content']; ?></td>
                <td><?php echo $this->Html->link($comment['resource_id'], 
                    '/resource/' . $comment['resource_id']); ?></td>
                <td><?php echo $user_info['User']['name']; ?></td>
                <td><?php echo $comment['created']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php  endif; ?>
</div>

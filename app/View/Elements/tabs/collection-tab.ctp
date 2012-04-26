<div class="tab-pane" id="collections-tab">
<?php if (empty($user_info['Collection'])): ?>
    <h4>No collections</h4>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Public?</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($user_info['Collection'] as $collection): ?>
            <?php if ($collection['temporary']) continue ?>
            <tr>
                <td>
                <?php if ($collection['public']): ?>
                    <span class="label success">Public</span>
                <?php else: ?>
                    <span class="label info">Private</span>
                <?php endif ?>
                <td>
                <?php echo $this->Html->link($collection['title'], 
                    '/collection/' . $collection['id']) ?>
                </td>
                <td><?php echo $collection['description'] ?></td>
                <td><?php echo $collection['created'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>

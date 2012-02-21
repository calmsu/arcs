<div class="tab-pane" id="collections-tab">
<?php if(empty($user_info['Collection'])): ?>
    <h3>Looks like this user hasn't made any discussion items yet</h3>
<?php  elseif(isset($user_info['Collection'])): ?>
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
                    <?php echo $this->Html->link(
                        $collection['title'], 
                        array(
                            'controller' => 'collection', 
                            'action' => 'view', $collection['id']
                    )) ?>
                </td> <!-- title -->
                <td><?php echo $collection['description'] ?></td><!-- description -->
                <td><?php echo $collection['created'] ?></td><!-- creation date -->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div><!-- #collections-tab -->

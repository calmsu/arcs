<div class="tab-pane active" id="uploads-tab">
<?php if (empty($user_info['Resource'])): ?>
    <h3>This user hasn't uploaded anything yet</h3>
<?php elseif (isset($user_info['Resource'])): ?>
    <table class="table table-striped">
        <thead>
            <tr>
            <th>Type</th>
            <th>Title</th>
            <th>Upload Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($user_info['Resource'] as $resource): ?>
            <tr>
                <td>
                    <i class="<?php 
                        if($resource['type'] == 'Photograph')
                            echo 'icon-picture';
                        else if($resource['type'] == 'Notebook')
                            echo 'icon-book';
                        else if($resource['type'] == 'Inventory Card')
                            echo 'icon-file';
                        else if($resource['type'] == 'Map')
                            echo 'icon-map-maker';
                        else
                            echo 'icon-file';
                        ?>"></i>
                </td><!-- type -->
                <td>
                    <?php echo $this->Html->link($resource['title'], 
                        '/resource/' . $resource['id'], 
                        array()
                    ) ?>
                </td><!-- title -->
                <td>
                    <?php echo $resource['created'] ?>
                </td> <!-- upload date -->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif ?>
</div><!-- #uploads-tab -->

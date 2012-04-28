<div class="tab-pane" id="annotations-tab">        		
<?php if(empty($user_info['Hotspot'])): ?>
    <h4>No Annotations</h4>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Title</th>
                <th>For</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($user_info['Hotspot'] as $annotation): ?>
            <tr>
                <td>
                    <?php if(isset($annotation['type'])) echo $annotation['type'] ?>
                </td> <!-- type -->
                <td> 
                    <?php echo $annotation['title'] ?>
                </td> <!-- title -->
                <td>
                    <?php echo $this->Html->link(
                        $annotation['resource_id'], 
                        '/resource/' . $annotation['resource_id']) ?>
                </td><!-- for -->
                <td>
                    <?php if(isset($annotation['created'])) echo $annotation['created'] ?>
                </td><!-- date -->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif ?>
</div><!-- #annotations-tab -->

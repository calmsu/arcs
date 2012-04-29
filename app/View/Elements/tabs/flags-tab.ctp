<div class="tab-pane" id="flagged-tab">
<?php if(!isset($user_info['Flags']) || empty($user_info['Flags'])): ?>
    <h4>No Flags</h4>
<?php elseif(isset($user_info['Annotation'])): ?>
    <label class="radio">
    <input type="radio" name="optionsRadios" 
        id="optionsRadios1" value="option1" checked=""> Discussion items
    </label>

    <label class="radio">
    <input type="radio" name="optionsRadios" 
        id="optionsRadios2" value="option2"> Discussions participated in
    </label>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Type</th>
                <th>Title</th>
                <th>Content</th>
                <th>For</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($user_info['Annotation'] as $flag): ?> 
            <tr>
                <td><?php echo $flag['type'] ?></td> <!-- type -->
                <td><?php echo $flag['title'] ?></td> <!-- title -->
                <td>					    			
                    <?php echo $this->Html->link($flag['resource_id'], 
                        '/resource/' . $flag['resource_id'], 
                        array());
                    ?>
                </td> <!-- for -->
                <td><?php $flag['created'] ?></td> <!-- date -->
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php  endif; ?>
</div><!-- #flagged-tab -->

<div class="tab-pane" id="annotations-tab">        		
<?php if(empty($user_info['Annotation'])): ?>
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
        </tbody>
    </table>
<?php endif ?>
</div><!-- #annotations-tab -->

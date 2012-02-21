        <div class="tab-pane" id="disucssion-tab">
        
        	<!-- <h1 class="alert alert-danger">The Discussion Tab is still under development</h1> -->
        	
        	<?php if(empty($user_info['Comment'])): ?>
			
				<h3>Looks like this user hasn't made any discussion items yet</h3>
			
			<?php  elseif(isset($user_info['Comment'])): ?>
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
			    			<td><?php echo $comment['content']; ?></td> <!-- content -->
			    			<td><?php echo $this->Html->link($comment['resource_id'], 
			    						'/resource/' . $comment['resource_id'], 
			    						array());
		    					?></td><!-- for -->
			    			<td><?php echo $user_info['User']['name']; ?></td> <!-- author -->
			    			<td><?php echo $comment['created']; ?></td><!-- created -->
			    		</tr>
			    	<?php endforeach; ?>
				  </tbody>
				</table>
			<?php  endif; ?>
        
        </div><!-- #discussion-tab -->
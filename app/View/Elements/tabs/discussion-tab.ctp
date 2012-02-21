        <div class="tab-pane" id="disucssion-tab">
        
        	<!-- <h1 class="alert alert-danger">The Discussion Tab is still under development</h1> -->
        	
        	<?php if(empty($user_info['Comment'])): ?>
			
				<h3>Looks like this user hasn't made any discussion items yet</h3>
			
			<?php  elseif(isset($user_info['Comment'])): ?>
	        	<label class="radio">
	           		<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked=""> Discussion items by Josh
	         	</label>
	         	
	         	<label class="radio">
	           		<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> Discussions Josh has participated in
	         	</label>
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
			    			<td><?php echo $this->Html->link($user_info['User']['name'], 
			    						'/resource/' . $comment['resource_id'], 
			    						array());
		    					?></td><!-- for -->
			    			<td><?php echo $comment['user_id']; ?></td> <!-- author -->
			    			<td><?php echo $comment['created']; ?></td><!-- created -->
			    		</tr>
			    	<?php endforeach; ?>
				  </tbody>
				</table>
			<?php  endif; ?>
        
        </div><!-- #discussion-tab -->
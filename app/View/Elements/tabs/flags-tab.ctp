        <div class="tab-pane" id="flagged-tab">
        	<!-- <h1 class="alert alert-danger">The Flags are still under development</h1> -->
			

			<?php if(!isset($user_info['Flags']) || empty($user_info['Flags'])): ?>
			
			<h3>Looks like this user hasn't made any Flags yet</h3>
			<?php elseif(isset($user_info['Hotspot'])): ?>
				<label class="radio">
	           		<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked=""> Discussion items by Josh
	         	</label>
	         	
	         	<label class="radio">
	           		<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> Discussions Josh has participated in
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
				    <?php foreach($user_info['Hotspot'] as $flag): ?> 
					    		<tr>
					    			<td><?php echo $flag['type'] ?></td> <!-- type -->
					    			<td><?php echo $flag['title'] ?></td> <!-- title -->
					    			<td>					    			
						    			<?php 
					    				echo 	$this->Html->link($flag['resource_id'], 
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
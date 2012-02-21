        <div class="tab-pane" id="annotations-tab">        		
	        <!-- <h1 class="alert alert-danger">Annotations tab is still under development</h1> -->
			
			<?php if(empty($user_info['Hotspot'])): ?>
			
				<h3>Looks like this user hasn't made any Annotations yet</h3>
			
			<?php  elseif(isset($user_info['Resource'])): ?>
				<table class="table table-striped">
		          <thead>
				    <tr>
				      <th>Flagged?</th>
				      <th>Type</th>
				      <th>Title</th>
				      <!-- <th>Content</th> -->
				      <th>For</th>
				      <th>Date</th>
				    </tr>
				  </thead>
				  <tbody>
			    <?php foreach($user_info['Hotspot'] as $annotation): ?>
							<tr>
				    			<?php 
					    			if(isset($annotation['flagged'])){
						    			if($annotation['flagged'] == 0){//not flagged annotation
						    				echo '<td><input type="checkbox" /></td>';#flagged
						    			} else if($annotation['flagged'] == 1){
						    				echo '<td><input type="checkbox" checked="checked" /></td>';#flagged
						    			}
					    			} else {
					    				echo '<td><input type="checkbox" /></td>';#flagged
					    			} 
				    			?>
				    			<td><?php if(isset($annotation['type'])){echo $annotation['type'];} ?></td> <!-- type -->
				    			<td><?php if(isset($annotation['title'])){echo $annotation['title'];} else { echo 'no title defined';} ?></td> <!-- title -->
				    			
				    			<td><?php 
				    				echo 	$this->Html->link($annotation['resource_id'], 
				    						'/resource/' . $annotation['resource_id'], 
				    						array());
				    				?>
				    			</td><!-- for -->
				    			
				    			<td><?php if(isset($annotation['created'])){echo $annotation['created'];} ?></td><!-- date -->
				    		</tr>
				    		
				    		<?php
								/*
								echo $this->Html->link(
									$annotation['type'],
								    array('controller' => 'resource', 'resource_id' => $annotation['resource_id']),
								);	
								*/
								
							?>
				    		
				    		
				  <?php endforeach; ?>
				  
			  <?php  endif;	?>
			  </tbody>
			</table>
        
        </div><!-- #annotations-tab -->
        
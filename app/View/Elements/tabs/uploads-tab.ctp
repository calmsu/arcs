<div class="tab-pane" id="uploads-tab">
	        <!-- <h1 class="alert alert-danger">Uploads tab is still under development</h1> -->
			<?php if(empty($user_info['Resource'])): ?>
			
				<h3>Looks like this user hasn't uploaded anything yet</h3>
			
			<?php elseif(isset($user_info['Resource'])): ?>
				<table class="table table-striped">
				  <thead>
				    <tr>
				      <th>Type</th>
				      <th>Title</th>
				      <th>Annotations</th>
				      <th><i class="icon-comment"></i></th><!-- comments -->
				      <th>Upload Date</th>
				    </tr>
				  </thead>
				  <tbody>
				    <?php foreach($user_info['Resource'] as $resource): ?>
				    		
				    		<tr>
								<td>
									<i class="<?php 
								    		if($resource['type'] == 'Photograph'){
								    			echo 'icon-picture';
								    		} else if($resource['type'] == 'Notebook'){
								    			echo 'icon-book';
								    		} else if($resource['type'] == 'Inventory Card'){
								    			echo 'icon-file';
								    		} else if($resource['type'] == 'Map'){
								    			echo 'icon-map-maker';
								    		}
								    	?>"></i>
								</td><!-- type -->
						<td><?php echo $this->Html->link($resource['id'], 
				    						'/resource/' . $resource['id'], 
				    						array());
						
						
						$resource['title']; ?></td><!-- title -->
						<td>24<!-- (this is a static number for now) --></td> <!-- annotations --> <!--  I need to know how to echo a cake-ified link in this instance that echoes the id of a resource...or: http://dev.cal.msu.edu:8080/~compto35/arcs/resources/view/' . $resource['id'] -->
						<td>240 <!-- (this is a static number for now) --></td> <!-- comments -->
						<td><?php echo $resource['created']; ?></td> <!-- upload date -->
				    			
</tr>
				    	<?php endforeach; ?>
						
				  </tbody>
				</table>
			<?php endif;//if/elseif ?>

        </div><!-- #uploads-tab -->
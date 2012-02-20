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
									<a href="">
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
									</a>
								</td><!-- type -->
						<td><a href=""><?php echo $resource['title']; ?></a></td><!-- title -->
						<td><a href="">24<!-- (this is a static number for now) --></a></td> <!-- annotations --> <!--  I need to know how to echo a cake-ified link in this instance that echoes the id of a resource...or: http://dev.cal.msu.edu:8080/~compto35/arcs/resources/view/' . $resource['id'] -->
						<td><a href="">240 <!-- (this is a static number for now) --></a></td> <!-- comments -->
						<td><?php echo $resource['created']; ?></td> <!-- upload date -->
				    			
</tr>
				    	<?php endforeach; ?>
						
				  </tbody>
				</table>
			<?php endif;//if/elseif ?>

        </div><!-- #uploads-tab -->
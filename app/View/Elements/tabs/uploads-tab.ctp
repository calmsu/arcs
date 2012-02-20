<div class="tab-pane" id="uploads-tab">
	        <h1 class="alert alert-danger">Uploads tab is still under development</h1>
			
			
			
			
			<?php if(empty($user_info['Resource'])){ ?>
			
				<h3>Looks like this user hasn't uploaded anything yet</h3>
			
			
			
			
			<?php  } else if(isset($user_info['Resource'])){ ?>
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
				    <?php
				    					    	
				    	foreach($user_info['Resource'] as $resource){
				    		
				    		function dataType(){
					    		if($user_info['Resource']['type'] == 'Photograph'){
					    			return 'icon-picture';
					    		} else if($user_info['Resource']['type'] == 'Notebook'){
					    			return 'icon-book';
					    		} else if($user_info['Resource']['type'] == 'Inventory Card'){
					    			return 'icon-file';
					    		} else if($user_info['Resource']['type'] == 'Map'){
					    			return 'icon-map-maker';
					    		}
					    	}//dataType
	

				    						    		
				    		echo '<tr>';
				    			echo '<td><a href=""><i class="' . dataType() . '"></i></a></td>';# type
				    			echo '<td><a href="">' . $resource['title'] . '</a></td>'; # title
				    			echo '<td><a href="">24 (this is a static number for now)</a></td>'; #annotations 
				    				##' . /*count($resource['annotations'])*/ . '
				    				## I need to know how to echo a cake-ified link in this instance that echoes the id of a resource...or: http://dev.cal.msu.edu:8080/~compto35/arcs/resources/view/' . $resource['id'] . '/##
				    			echo '<td><a href="">24 (this is a static number for now)</a></td>';#comments
				    				##' . /*count($resource['annotations'])*/ . '
				    			echo '<td>' . $resource['created'] . '</td>'; #upload date
				    			
				    		echo '</tr>';
				    	}//foreach
						
						/* 
						#Echo out some placeholding rows like a boss
						$i = 0;
						while ($i <= 10) {
							echo '<tr>';
							    echo '<td><a href=""><i class="icon-picture"></i> Photo</a></td><!-- type -->';
							    echo '<td><a href="">3</a></td><!-- title -->';
							    echo '<td><a href="">24</a></td><!-- annotation -->';
							    echo '<td><a href="">154</a></td><!-- discussion -->';
							    echo '<td>12/12/12</td><!-- upload date -->';
						    echo '</tr>';
						    $i++;
						}
						*/
					?>
				  </tbody>
				</table>
			<?php }//if/elseif ?>

        </div><!-- #uploads-tab -->
        <div class="tab-pane" id="disucssion-tab">
        
        	<!-- <h1 class="alert alert-danger">The Discussion Tab is still under development</h1> -->
        	
        	<?php if(empty($user_info['Comment'])){ ?>
			
				<h3>Looks like this user hasn't made any discussion items yet</h3>
			
			<?php  } else if(isset($user_info['Comment'])){ ?>
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
				    <?php
				    	foreach($user_info['Comment'] as $comment){
				    		echo '<tr>';
				    			echo '<td><a href="">' . $comment['content'] . '</a></td>'; # content
				    			echo '<td><a href="">' . $comment['resource_id'] . '</a></td>'; # for
				    			echo '<td><a href="">' . $comment['user_id'] . '</a></td>'; # author
				    			echo '<td><a href="">' . $comment['created'] . '</a></td>'; # created
				    		echo '</tr>';
				    	}//foreach
				    	/*
						#Echo out some placeholding rows like a boss
						$i = 0;
						while ($i <= 10) {
							echo '<tr>';
							    echo '<td><a href="">Notebook spread 11-12</a></td><!-- for -->';
							    echo '<td><a href="">Jon Frey</a></td><!-- author -->';
							    echo '<td>12/12/12</td><!-- date-->';
						    echo '</tr>';
						    $i++;
						}*/
					?>
				  </tbody>
				</table>
				
			<?php  }//if/elseif	//$flags = $user_info['Comment']; ?>
        
        </div><!-- #discussion-tab -->
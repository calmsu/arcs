        <div class="tab-pane" id="annotations-tab">        		
	        <!-- <h1 class="alert alert-danger">Annotations tab is still under development</h1> -->
			
			<?php if(empty($user_info['Hotspot'])){ ?>
			
				<h3>Looks like this user hasn't made any Annotations yet</h3>
			
			<?php  } else if(isset($user_info['Resource'])){ ?>
				<label class="radio">
	           		<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked=""> Discussion items by Josh
	         	</label>
	         	
	         	<label class="radio">
	           		<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2"> Discussions Josh has participated in
	         	</label>
				
				<table class="table table-striped">
		          <thead>
				    <tr>
				      <th>Flagged?</th>
				      <th>Type</th>
				      <th>Title</th>
				      <th>Content</th>
				      <th>For</th>
				      <th>Date</th>
				    </tr>
				  </thead>
				  <tbody>
			    <?php
				    	foreach($user_info['Hotpot'] as $annotation){
				    		echo '<tr>';
				    			if($annotation['flagged'] == 0){//not flagged annotation
				    				echo '<td><input type="checkbox" /></td>';#flagged
				    			} else if($annotation['flagged'] == 1){
				    				echo '<td><input type="checkbox" checked="checked" /></td>';#flagged
				    			}
				    			echo '<td><a href="">' . $annotation['type'] . '</a></td>'; # type
				    			echo '<td><a href="">' . $annotation['title'] . '</a></td>'; # title
				    			echo '<td><a href="">' . $annotation['resource_id'] . '</a></td>'; # for
				    			echo '<td><a href="">' . $annotation['created'] . '</a></td>'; # date
				    		echo '</tr>';
				    	}//foreach
				  ?>
				  
				  <?php  }//if/elseif	?>
			  </tbody>
			</table>
        
        </div><!-- #annotations-tab -->
        
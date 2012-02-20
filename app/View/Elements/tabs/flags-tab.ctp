        <div class="tab-pane" id="flagged-tab">
        
        	<h1 class="alert alert-danger">The Flags are still under development</h1>
			<?php if(empty($user_info['Hotspot'])){ ?>
			
			<h3>Looks like this user hasn't made any Flags yet</h3>
			<?php  } else if(isset($user_info['Hotspot'])){ ?>
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
				    <?php
					    	foreach($user_info['Hotspot'] as $flag){
					    		echo '<tr>';
					    			echo '<td><a href="">' . $flag['type'] . '</a></td>'; # type
					    			echo '<td><a href="">' . $flag['title'] . '</a></td>'; # title
					    			echo '<td><a href="">' . $flag['resource_id'] . '</a></td>'; # for
					    			echo '<td><a href="">' . $flag['created'] . '</a></td>'; # date
					    		echo '</tr>';
					    	}//foreach
					?>
				  </tbody>
				</table>
        		<?php  }//if/elseif	//$flags = $user_info['Comment']; ?>
        </div><!-- #flagged-tab -->
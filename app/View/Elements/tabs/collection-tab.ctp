<div class="tab-pane" id="collections-tab">
	<h1 class="alert alert-danger">This is still under development</h1>
	
	
	<?php if(empty($user_info['Collection'])){ ?>
	
		<h3>Looks like this user hasn't made any discussion items yet</h3>
	
	<?php  } else if(isset($user_info['Collection'])){ ?>
	
	
	
	
	
<?php /*	
	[id] => 4f2b1ac2-e71c-4b9f-bfd4-50452308e057
    [title] => Virtual collection
    [description] => Results from search, 'null'
    [public] => 
    [user_id] => 4f22d484-a8ec-49c1-aca2-1f082308e057
    [pdf] => 
    [created] => 2012-02-02 18:22:42
    [modified] => 2012-02-02 18:22:42
    [temporary] => 	
*/?>
	
	
	
		<table class="table table-striped">
		  <thead>
		    <tr>
			    <th>Public?</th>
			    <th>Title</th>
			    <th>Description</th>
			    <th>Date</th>
		    </tr>
		  </thead>
		  <tbody>
		    <?php
		    
		    
		    
		    
		    foreach($user_info['Collection'] as $collection){
	    		echo '<tr>';
				    if($collection['public'] == 'true'){//not flagged annotation
	    				echo '<td><a class="btn success">Public</a></td>';#public
	    			} else {
	    				echo '<td><a class="btn danger">Private</a></td>';#private
	    			}
				   echo '<td><a href="">' . $collection['title'] . '</a></td>';#title
				   echo '<td>' . $collection['description'] . '</td>';#description
				   echo '<td>' . $collection['created'] . '</td>';#creation date
			    echo '</tr>';
	    	}//foreach
	    					
			?>
		  </tbody>
		</table>

	
	<?php  }//if/elseif	//$flags = $user_info['Comment']; ?>
	    
</div><!-- #collections-tab -->

<div class="tab-pane" id="collections-tab">
	<h1 class="alert alert-danger">This is still under development</h1>
	<table class="table table-striped">
	  <thead>
	    <tr>
	      <th>Flagged?</th>
	      <th>Type</th>
	      <th>Title</th>
	      <th>For</th>
	      <th>Date</th>
	    </tr>
	  </thead>
	  <tbody>
	    <?php
			#Echo out some placeholding rows like a boss
			$i = 0;
			while ($i <= 10) {
				echo '<tr>';
				    echo '<td><input type="checkbox" /></td><!-- flagged -->';
				    echo '<td><a href=""><i class="icon-picture"></i> Photo</a></td><!-- type -->';
				    echo '<td><a href="">Awwwwwwww yeah</a></td><!-- title -->';
				    echo '<td><a href="">Notebook spread pp100-101</a></td><!-- for -->';
				    echo '<td>12/12/12</td><!-- upload date -->';
			    echo '</tr>';
			    $i++;
			}
			
			/*
			foreach($user_info['Annotation'] as $annotation){
	    		echo '<tr>';
	    			echo '<td><input type="checkbox" checked="checked" /></td>'; # flagged
	    			
	    			function annotation_type(){
		    			if($annotation-['type'] == 0){
		    				//echo 'icon-picture'; #photo
		    				return array ([class] => 'icon-picture', [text] => 'Photo';);
		    				
		    			} else if($annotation['type'] == 1){
		    				//echo 'icon-tag'; #resource card
		    				return array ([class] => 'icon-tag', [text] => 'Resource Card');
		    				
		    			} else if($annotation['type'] == 2){
		    				//echo 'icon-file'; #report
							return array ([class] => 'icon-file', [text] => 'Report');
		    			}
	    			}
	    			
	    			$annotation_type = annotation_type();
	    			
	    			echo '<td><a href=""><i class="' . echo $annotation_type['class'] . '"></i>' . echo $annotation_type['text'] . '</a></td>'; # type
	    			echo '<td><a href="">' . $annotation['title'] . '</a></td>'; # title
	    			echo '<td><a href="">24 (this is a static number for now)</a></td>'; #annotations 
	    				##' . count($annotation['annotations']) . '
	    				## I need to know how to echo a cake-ified link in this instance that echoes the id of a resource...or: http://dev.cal.msu.edu:8080/~compto35/arcs/resources/view/' . $resource['id'] . '/##
	    			echo '<td><a href="">24 (this is a static number for now)</a></td>';#comments
	    				##' . count($annotation['annotations']) . '
	    			echo '<td>' . $annotation['created'] . '</td>'; #upload date
	    			
	    		echo '</tr>';
	    	}//foreach
	    	*/
		?>
	  </tbody>
	</table>
    
</div><!-- #collections-tab -->

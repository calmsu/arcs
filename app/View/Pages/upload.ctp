<section id="upload-form" class="row">
	<div class="span12">
		<h1 id="logo">New Arcs Resource</h1>
	</div>
	
	<form class="span12 well" name="upload-pt1">
		<label for="new-resource" class="btn primary large unselectable">
			<input type="radio" name="new-or-add" id="new-resource" value="new-resource" /> 
			New Resource
		</label>
		<label for="add-to-notebook" class="btn large unselectable">
			<input type="radio" name="new-or-add" id="add-to-notebook" value="add-to-notebook" /> 
			Add to Existing Notebook
		</label>
	
		<select id="resource-type">
		  <option disabled="disabled">Choose Resource Type</option>
		  <option>Photo</option>
		  <option>Resource Card</option>
		</select>
		
		<select id="choose-notebook">
			<option disabled="disabled"> Choose a Notebook</option>
			<option>Notebook 1</option>
			<option>Notebook 2</option>
			<option>Notebook 3</option>
			<option>Notebook 4</option>
			<option>Notebook 5</option>
			<option>Notebook 6</option>
		</select>
		
        <a class="btn info large">
        	<i class="icon-white icon-plus"></i>
        	<input id="file-input" type="file" />
        </a>
	    
    </form><!--name=upload -->
	    
    <form class="span12 well" name="upload-pt2">
    	<fieldset class="properties">
    		<legend>Properties</legend>
    		<?php echo $this->Html->image('placeholder.png',
				array(	'alt'=>'Preview of thumbnail', 
						'class' => 'span2 columns', 
						'title' => 'Bask in the glory of previewed stuff', 
						'width' => 'auto')); 
			?>
			
			<div class="properties-contain">
				<div class="label-input-wrapper">
					<label for="resource-title">Title</label>
					<input type="text" class="large" name="resource-title" id="resource-title" placeholder="give this baby a title!" />
				</div>
				
				<div class="label-input-wrapper">
					<label for="resource-description">Description</label>
					<textarea name="resource-description" id="resource-description" placeholder="Something helpful to make this distinct from other resources"></textarea>
				</div>

				<div class="label-input-wrapper">
					<label for="original-date">Original Date</label>
					<input type="date" name="original-date" id="original-date" />
				</div>
				
				<div class="label-input-wrapper">
					<label for="excavators">Excavator</label>
					<select multiple="multiple" name="excavators" id="excavators">
						<option>Jon Fry</option>
						<option>Another one</option>
						<option>Yay!</option>
					</select>
				</div>
				
				<div class="label-input-wrapper">
					<label for="area">Area</label>
					<select name="area" id="area">
						<option>Area 1</option>
						<option>Area 2</option>
						<option>Area 3</option>
					</select>
				</div>
				
				<div class="label-input-wrapper">
					<label for="trench">Trench</label>
					<select name="trench" id="trench">
						<option>Jon Fry</option>
						<option>Another one</option>
						<option>Yay!</option>
					</select>
				</div>
				
				<div class="label-input-wrapper">
					<label for="tags">Tags</label>
					<input type="text" name="tags" id="tags" />
				</div>				
			</div><!-- rest of properties stuff -->
    	</fieldset><!-- .properties-contain -->
    	
    	<fieldset class="privacy">
    		<legend>Privacy</legend>
    		<div class="privacy-contain">
	    		<div class="label-input-wrapper">
		    		<label for="annotation-permissions">Annotating Permissions</label>
		    		
		    		<div class="radio-group">
			    		<input type="radio" name="annotation-permissions" id="annotate-open" value="open"/>
			    		<label for="annotate-open">Open</label>
			    		
			    		<input type="radio" name="annotation-permissions" id="annotate-collaborators" value="collaborators" />
			    		<label for="annotate-collaborators">Exclusive to Collaborators, Sr. Researchers, & Mods</label>
		    		</div>
	    		</div>
	    		
	    		<div class="label-input-wrapper">
		    		<label for="primary-researcher">Primary Researcher</label>
		    		<input type="text" name="primary-researcher" id="primary-researcher" placeholder="Who should own this?" />
	    		</div>
	    		
	    		<div class="label-input-wrapper">
		    		<label for="other-researchers">Other Researchers</label>
		    		<input type="text" name="other-researchers" id="other-researchers" placeholder="Who else can Collaborate on this?" />
		    		<select class="already-added" multiple="multiple">
		    			<option>Person I've input already</option>
		    			<option>Another Person</option>
		    			<option>Yet another</option>
		    		</select>
				</div>
    		</div><!-- .privacy-contain -->
    	</fieldset><!-- .privacy -->
    	<a class="btn large pull-right"><i class="icon-remove-sign"></i> Cancel</a>
    	<a class="btn primary large pull-right"><i class="icon-white icon-ok-sign"></i> Save</a>

    </form><!-- upload-pt2 -->
</section><!-- #logo-search-filter -->

<?php echo $this->Html->script('toggle-panels.js'); ?>


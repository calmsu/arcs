<?php #user view page ?>
<section id="logo-search-filter" class="row">
	<form>
		<div id="arcs-search-wrapper">
    		<input name="main-search" id="main-search" class="span8 unselectable" value="" placeholder="" autofocus="">
    		<a class="btn large unselectable" data-original-title=""><i class="icon-search"></i>  Search</a>
		</div><!-- search-wrapper -->
    
		<div class="panel-wrapper">
    		<a href="#" id="filter-panel-toggle" class="unselectable">Filter</a>
	    	<div class="panel-content">
	    		<label for="all-the-things">All of ARCS</label>
			   	<input type="radio" name="all-the-things" id="all-the-things" value="all">
			   
				<label for="only-this-thing">All of ARCS</label>
				<input type="radio" name="only-this-thing" id="only-this-thing" value="this-thing">
			   
				<hr>
			   
			   	<label for="datepicker">Date</label>
			   	<input type="date" value="" name="datepicker" id="datepicker">
	    	</div><!-- .filter-panel-container -->
	    </div><!-- .filter-panel-wrapper -->			
    	
	    
    </form><!-- main search -->
</section><!-- #logo-search-filter -->
<div class="row" id="user-info"> <!-- class="row span12"> -->
	<?php 
			echo $this->Html->image('indi.jpg',
			array(	'alt'=> $user['name'] . '\'s picture', 
					'id' => 'prof-img', 
					'class' => 'span2', 
					'title' => 'Bask in the glory of' . $user['name'], 
					#'width' => '50'
				)); 
	?>
	<div class="span10">
		<?php 
			echo "<h2>".$user['name']."</h2>"; 
		?>
		<p><strong class="label label-info">Institution:</strong> <?php #echo $user['institution'] ?> Michigan State University</p>
		<p>
			<strong class="label label-info">Status:</strong> 
			<?php #echo $user['ed-status']; ?>Undergrad, class 2012
		</p>
		
		<p>
			<strong class="label label-info">Role:</strong> 
			<?php 
				$role = $user_info['User']['role'];
				if($role == 0){
					echo "Moderator";
				} else if($role == 1){
					echo "Sr Researcher";
				} else if($role == 2){
					echo "Jr Researcher";
				}
				?>
		</p>
		
		<p><strong class="label label-info">Email:</strong> <a href="mailto:compto35@gmail.com"><?php echo $user['email']; ?></a></p>
	</div>
</div><!-- user-info -->

<div class="row" id="user-actions"> <!-- class="row span12">--> <!--class="tab-wrapper">-->
    <ul class="nav tabs">
        <li><a data-toggle="tab" href="#activity-tab">Activity</a></li>
        <li><a data-toggle="tab" href="#uploads-tab">Uploads</a></li>
        <li><a data-toggle="tab" href="#annotations-tab">Annotations</a></li>
        <li><a data-toggle="tab" href="#flagged-tab">Flagged Items (12)</a></li>
        <li><a data-toggle="tab" href="#disucssion-tab">Discussions (122)</a></li>
        <li><a data-toggle="tab" href="#collections-tab">Collections</a></li>
        <li class="active"><a data-toggle="tab" href="#print-arrays">Super Secret Code Tab</a></li>
    </ul><!-- .tab-heads -->

    <div class="tab-content">
    	<?php echo $this->element('tabs/activity-tab') ?>
	    <?php echo $this->element('tabs/uploads-tab') ?>
	    <?php echo $this->element('tabs/annotations-tab') ?>
	    <?php echo $this->element('tabs/flags-tab') ?>
	    <?php echo $this->element('tabs/discussion-tab') ?>
	    <?php echo $this->element('tabs/collection-tab') ?>
	    <?php echo $this->element('tabs/print-arrays') ?>
               
    </div><!-- sidebar-tab-content -->		    

</div><!-- tab-wrapper -->

    

<?php echo $this->Html->script('toggle-panels.js'); ?>
<?php # debug($user_info) ?>
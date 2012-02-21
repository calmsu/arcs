<div class="row" id="user-info"> <!-- class="row span12"> -->
	<div class="span10">
        <h2><?php echo $user_info['User']['name'] ?></h2>
		<p>
			<strong class="label label-info">Role:</strong> 
            <?php 
                $role = $user_info['User']['role'];
                if ($role == 0) echo "Admin";
                if ($role == 1) echo "Moderator";
                if ($role == 2) echo "Researcher";
            ?>
		</p>
        <p>
            <strong class="label label-info">Email:</strong>
            <a href="mailto:<?php echo $user_info['User']['email'] ?>">
                <?php echo $user_info['User']['email']; ?>
            </a>
        </p>
	</div>
</div><!-- user-info -->

<div class="row" id="user-actions">
    <ul class="nav tabs">
        <li class="active"><a data-toggle="tab" href="#uploads-tab">Uploads</a></li>
        <li><a data-toggle="tab" href="#annotations-tab">Annotations</a></li>
        <li><a data-toggle="tab" href="#flagged-tab">Flagged Items</a></li>
        <li><a data-toggle="tab" href="#disucssion-tab">Discussions</a></li>
        <li><a data-toggle="tab" href="#collections-tab">Collections</a></li>
    </ul><!-- .tab-heads -->

    <div class="tab-content">
	    <?php echo $this->element('tabs/uploads-tab') ?>
	    <?php echo $this->element('tabs/annotations-tab') ?>
	    <?php echo $this->element('tabs/flags-tab') ?>
	    <?php echo $this->element('tabs/discussion-tab') ?>
	    <?php echo $this->element('tabs/collection-tab') ?>
               
    </div><!-- sidebar-tab-content -->		    
</div><!-- tab-wrapper -->

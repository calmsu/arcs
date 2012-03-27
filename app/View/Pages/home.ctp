<section id="logo-search-filter">
    	<h1 id="logo">ARCS</h1>
    	
    	<form>
    		<div id="arcs-search-wrapper">
	    		<input name="main-search" id="main-search" class="span8 unselectable" value="" placeholder="" autofocus="">
	    		<a class="btn icon large unselectable" data-original-title=""><span class="search"></span>Search</a>
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
    
    <details class="arcs-front-page unselectable" open="open">
    	<summary class="large">Notebooks</summary>
        <div>
        </div><!-- details-content -->
    </details><!-- arcs front page -->
    
    <details class="arcs-front-page unselectable">
    	<summary class="large">Photographs</summary>
        <div>
        </div><!-- details-content -->
    </details><!-- arcs front page -->
    
    <details class="arcs-front-page unselectable">
    	<summary class="large">Artifacts</summary>
        <div>
        </div><!-- details-content -->
    </details><!-- arcs front page -->
    
    <details class="arcs-front-page unselectable">
    	<summary class="large">Maps</summary>
        <div>
        	<ul class="resource-thumbs">
                             
                <li>
                    <img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/8/7_7f35f89a12d7171/5387thm_cb6590b40733ea8.jpg" alt="" style="width:100px; height:90px;" id="1" data-id="5387">
                    <a href="#">Notebook Title</a>
                </li>
                                 
                <li>
                    <img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/8/8_5d29bb6b18d4d34/5388thm_06ea14ae5668074.jpg" alt="" style="width:100px; height:90px;" id="2" data-id="5388">
                    <a href="#">Notebook Title</a>
                </li>
                                 
                <li>
                    <img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/8/9_65c99657571a32c/5389thm_90654a4109c7da8.jpg" alt="" style="width:100px; height:90px;" id="3" data-id="5389">
                    <a href="#">Notebook Title</a>
                </li>
                                 
                <li>
                    <img class="selected" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/0_cc7a80f0bf90229/5390thm_6f6a1c44e8cf599.jpg" alt="" style="width:100px; height:90px;" id="4" data-id="5390">
                    <a href="#">Notebook Title</a>
                </li>
                                 
                <li>
                    <img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/1_8e2df666e00e366/5391thm_37534ceae3fa558.jpg" alt="" style="width:100px; height:90px;" id="5" data-id="5391">
                    <a href="#">Notebook Title</a>
                </li>
                                 
			</ul>
        </div><!-- details-content -->
    </details><!-- arcs front page -->
    
    <details class="arcs-front-page unselectable">
    	<summary class="large">Discussion</summary>
        <div>
        	<ul class="resource-thumbs">
                             
                <li>
                    <img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/8/7_7f35f89a12d7171/5387thm_cb6590b40733ea8.jpg" alt="" style="width:100px; height:90px;" id="1" data-id="5387">
                    <a href="#">Notebook Title</a>
                </li>
                                 
                <li>
                    <img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/8/8_5d29bb6b18d4d34/5388thm_06ea14ae5668074.jpg" alt="" style="width:100px; height:90px;" id="2" data-id="5388">
                    <a href="#">Notebook Title</a>
                </li>
                                 
                <li>
                    <img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/8/9_65c99657571a32c/5389thm_90654a4109c7da8.jpg" alt="" style="width:100px; height:90px;" id="3" data-id="5389">
                    <a href="#">Notebook Title</a>
                </li>
                                 
                <li>
                    <img class="selected" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/0_cc7a80f0bf90229/5390thm_6f6a1c44e8cf599.jpg" alt="" style="width:100px; height:90px;" id="4" data-id="5390">
                    <a href="#">Notebook Title</a>
                </li>
                                 
                <li>
                    <img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/1_8e2df666e00e366/5391thm_37534ceae3fa558.jpg" alt="" style="width:100px; height:90px;" id="5" data-id="5391">
                    <a href="#">Notebook Title</a>
                </li>
                                 
			</ul>
        </div><!-- details-content -->
    </details><!-- arcs front page -->

</div><!-- page fluid-container -->

<footer id="credits">	
	<div class="row">
		<div class="span11"?>
			<div id="quick-links">
				<h3>Quick Links</h3>
					<ul class="inline">
						<li><?php echo $this->Html->link('Home', '/pages/home', array('class' => 'label label-footer')); ?></li>
						<li><?php echo $this->Html->link('About', '/pages/about', array('class' => 'label label-footer')); ?></li>
						<li><?php echo $this->Html->link('Help', '/pages/help', array('class' => 'label label-footer')); ?></li>
					</ul>
			</div>
			<div id="sponsors">
				<h3>Our Sponsors</h3>
				<?php 
					echo $this->Html->image( 'neh-logo.png',
						array(	'alt'=> 'NEH Logo', 
								'id' => 'neh_logo', 
								'class' => 'logo', 
								'title' => 'Any views, findings, conclusions, or recommendations expressed in this website do not necessarily represent those of the National Endowment for the Humanities', 
								'url' => 'http://www.neh.gov/'
							)); 
					echo $this->Html->image( 'msu-wordmark.png',
						array(	'alt'=> 'MSU Wordmark', 
								'id' => 'msu_wordmark', 
								'class' => 'logo', 
								'title' => 'Any views, findings, conclusions, or recommendations expressed in this website do not necessarily represent those of Michigan State University', 
								'url' => 'http://www.msu.edu/'
							)); 
				?>
				
				<?php 
					echo $this->Html->image( 'cal-white-masthead.png',
						array(	'alt'=> 'CE Logo', 
								'id' => 'ce_logo', 
								'class' => 'logo', 
								'title' => 'Any views, findings, conclusions, or recommendations expressed in this website do not necessarily represent those of the College of Arts and Letters', 
								'url' => 'http://cal.msu.edu'
						)); 
				?>
				
				<?php 
					echo $this->Html->image( 'ce-logo.png',
						array(	'alt'=> 'CE Logo', 
								'id' => 'ce_logo', 
								'class' => 'logo', 
								'title' => 'Any views, findings, conclusions, or recommendations expressed in this website do not necessarily represent those of the Creativity Exploratory', 
								'url' => 'http://ce.cal.msu.edu'
						)); 
				?>
					
				<p class="disclaimer">Any views, findings, conclusions, or recommendations expressed in this website do not necessarily represent those of the National Endowment for the Humanities, Michigan State University, the College of Arts and Letters, or the Creativity Exploratory.</p>
		
			</div><!-- #sponsors -->
		</div><!-- .span11 -->
		<div id="about-arcs-footer" class="span3">
			<h3>About This Site</h3>
			<p>ARCS is&hellip;Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. <?php echo $this->Html->link('read more...', '');?></p>
		</div><!-- #about-arcs-footer -->
	
	
		<div id="copyright-and-credits" class="span12">
			<p>A product of <a href="http://ce.cal.msu.edu/" title="Creativity Exploratory at College of Arts & Letters">CE@CAL</a></p>
			<p>&copy;<?php echo date("Y"); ?> Creativity Exploratory, MSU</p>
		</div><!-- #copyright-and=credits -->
	</div><!-- .row -->
</footer>


<?php echo $this->Html->script('toggle-panels.js'); ?>
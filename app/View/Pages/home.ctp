<section id="logo-search-filter">
    	<h1 id="logo">ARCS</h1>
    	
    	<form>
    		<div id="arcs-search-wrapper">
	    		<input name="main-search" id="main-search" class="span8 unselectable" value="" placeholder="" autofocus="">
	    		<a class="btn icon large unselectable" data-original-title=""><span class="search"></span>Search</a>
    		</div><!-- search-wrapper -->    
	    </form><!-- main search -->
    </section><!-- #logo-search-filter -->
    
    <details class="arcs-front-page unselectable" open="open">
    	<summary class="large">Notebooks</summary>
        <div>
        	<ul class="resource-thumbs">                  
                <li>
                    <a href="#">
                    	<img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/1_8e2df666e00e366/5391thm_37534ceae3fa558.jpg" alt="" data-id="5391">
                    </a>
                    <a href="#">Notebook Title</a>
                </li>
                <li>
                    <a href="#">
                    	<img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/1_8e2df666e00e366/5391thm_37534ceae3fa558.jpg" alt="" data-id="5391">
                    </a>
                    <a href="#">Notebook Title</a>
                </li>
                <li>
                    <a href="#">
                    	<img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/1_8e2df666e00e366/5391thm_37534ceae3fa558.jpg" alt="" data-id="5391">
                    </a>
                    <a href="#">Notebook Title</a>
                </li>
				<li>
                    <a href="#">
                    	<img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/1_8e2df666e00e366/5391thm_37534ceae3fa558.jpg" alt="" data-id="5391">
                    </a>
                    <a href="#">Notebook Title</a>
                </li>
				<li>
                    <a href="#">
                    	<img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/1_8e2df666e00e366/5391thm_37534ceae3fa558.jpg" alt="" data-id="5391">
                    </a>
                    <a href="#">Notebook Title</a>
                </li>

				<li>
                    <a href="#">
                    	<img class="" src="http://arcs.dev.cal.msu.edu/filestore/5/3/9/1_8e2df666e00e366/5391thm_37534ceae3fa558.jpg" alt="" data-id="5391">
                    </a>
                    <a href="#">Notebook Title</a>
                </li>
                                 
			</ul>
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
    </details><!-- arcs front page -->
    
    <details class="arcs-front-page unselectable">
    	<summary class="large">Discussion</summary>
        <div>
        </div><!-- details-content -->
    </details><!-- arcs front page -->

</div><!-- page fluid-container -->

<footer id="credits" class="container">	
	<div class="row">

		<div id="quick-links" class="span2">
			<!-- <h3>Quick Links</h3> -->
				<ul class="inline">
					<li><?php echo $this->Html->link('Home', '/pages/home', array('class' => 'label-footer')); ?></li>
					<li><?php echo $this->Html->link('About', '/pages/about', array('class' => 'label-footer')); ?></li>
					<li><?php echo $this->Html->link('Help', '/pages/help', array('class' => 'label-footer')); ?></li>
				</ul>
		</div>
		<div id="sponsors" class="span7">
			<!-- <h3>Our Sponsors</h3> -->
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
					array(	'alt'=> 'CAL Logo', 
							'id' => 'cal_logo', 
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

		<div id="about-arcs-footer" class="span2">
			<!-- <h3>About This Site</h3> -->
			<p>ARCS is&hellip;Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante.<?php echo $this->Html->link('read more...', '');?></p>
		</div><!-- #about-arcs-footer -->
	
	
		<div id="copyright-and-credits" class="span12">
			<p>A product of <a href="http://ce.cal.msu.edu/" title="Creativity Exploratory at College of Arts & Letters">CE@CAL</a></p>
			<p>&copy;<?php echo date("Y"); ?> Creativity Exploratory, MSU</p>
		</div><!-- #copyright-and=credits -->
	</div><!-- .row -->
</footer>


<?php echo $this->Html->script('toggle-panels.js'); ?>
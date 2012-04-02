<footer id="credits">
	<div class="container">
		<div class="row">
			<div id="quick-links" class="span2">
			    <?php echo $this->Html->link('Home', '/pages/home') ?> |
			    <?php echo $this->Html->link('About', '/pages/about') ?> |
			    <?php echo $this->Html->link('Search', '/search') ?> |
			    <?php echo $this->Html->link('Help', '/help') ?>
			</div><!-- #quick-links -->
	
			<div id="sponsors" class="span7">
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
				<p>&copy;<?php echo date("Y"); ?> Michigan State University Board of Trustees</p>
			</div><!-- #copyright-and=credits -->
		</div><!-- .row -->
	</div><!-- .container -->
</footer>


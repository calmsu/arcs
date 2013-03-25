<div class="footer">
    <?php 
    echo $this->Html->link(
        $this->Html->image('neh-logo.png'),
        'http://neh.gov',
        array('escape' => false)
    );
    echo $this->Html->link(
        $this->Html->image('cal-white-masthead.png'),
        'http://cal.msu.edu',
        array('escape' => false)
    );
    echo $this->Html->link(
        $this->Html->image('ce-logo.png'),
        'http://ce.cal.msu.edu',
        array('escape' => false)
    );
    ?>
    <br>
    <span class="disclaimer">
Any views, findings, conclusions, or recommendations expressed in this website do not necessarily represent those of the National Endowment for the Humanities or Michigan State University.
    </span>
    <hr>
    <?php echo $this->Html->link('About', '/about') ?> /
    <?php echo $this->Html->link('Home', '/') ?> /
    <?php echo $this->Html->link('Search', '/search') ?> /
    <?php echo $this->Html->link('Help', '/help') ?> /
    <?php echo $this->Html->link('Login', '/login') ?>
    &nbsp;&nbsp;
    &copy; 2012-2013 Michigan State University Board of Trustees
</div>

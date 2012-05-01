<?php
/**
 * Docs Controller
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class HelpController extends AppController {

	public $name = 'Help';

	public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('display');
        $this->set('toolbar', array(
            'logo' => true
        ));
        $structure = file_get_contents(ROOT . DS . 'docs' . DS . 'sidebar.json');
        $this->set('sidebar', json_decode($structure, true));
        $this->layout = 'doc';
    }

    /**
     * Displays a document
     *
     * @param doc
     * @return void
     */
	public function display($doc) {
        $title_for_layout = 'ARCS Help';
        $active = $doc == 'index' ? '' : $doc;
        $this->set('active', $active);
		$this->render($doc);
	}
}

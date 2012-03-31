<?php
/**
 * Docs Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
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
        $this->set('docs', array(
            'Help' => null,
            'Getting Started' => '',
            'Resources' => 'about-resources',
            'Collections' => 'about-collections',
            'Uploading Resources' => 'uploading',
            'Your Account' => 'account',
            'Searching' => 'searching',
            'Bulk Actions' => 'bulk-actions',
            'Developer' => null,
            'Installing ARCS' => 'installing',
            'API Reference' => 'developer-api',
        ));
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

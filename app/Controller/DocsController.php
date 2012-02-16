<?php
/**
 * Docs Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class DocsController extends AppController {

	public $name = 'Docs';

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
            'Searching the Catalog' => 'searching',
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
	public function display($doc='index') {
        $title_for_layout = 'ARCS Help';
        $this->set('active', $doc);
		$this->render($doc);
	}
}

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
            'logo' => true,
            'buttons' => array(
                array(
                    'id' => 'test',
                    'class' => 'link',
                    'content' => 'Arcs at Ismia',
                    'url' => '#'
                ),
                array(
                    'id' => 'test',
                    'class' => 'image',
                    'content' => 'Test',
                    'url' => '#'
                ),
            )
        ));
        $this->layout = 'doc';
    }

    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     */
	public function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = null;
        $title_for_layout = 'ARCS Help';

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
}

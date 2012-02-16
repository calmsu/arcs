<?php
/**
 * Pages Controller
 * 
 * @package      ARCS
 * @copyright    Copyright 2012, Michigan State University Board of Trustees
 */
class PagesController extends AppController {

	public $name = 'Pages';

	public $uses = array();

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('display');
    }

    /**
     * Displays information about the system configuration.
     */
    public function status() {
        if ($this->Auth->user('role') > 0) {
            $this->setFlash('You must be an admin user to view the status page.');
            $this->redirect('/');
        }
        $uploads_path = Configure::read('paths.uploads');
        $this->set('uploads', array(
            'url' => Configure::read('urls.uploads'),
            'path' => $uploads_path,
            'exists' => is_dir($uploads_path),
            'writable' => is_writable($uploads_path),
            'executable' => is_executable($uploads_path)
        ));
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
		$page = $subpage = $title_for_layout = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title_for_layout = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title_for_layout'));
		$this->render(implode('/', $path));
	}
}

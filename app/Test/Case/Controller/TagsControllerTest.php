<?php
App::uses('Controller', 'Controller');
App::uses('TagsController', 'Controller');

/**
 * TestTagsController *
 */
class TestTagsController extends TagsController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * TagsController Test Case
 *
 */
class TagsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.tag', 'app.user', 'app.resource', 'app.membership', 'app.collection', 'app.comment', 'app.hotspot', 'app.bookmark');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Tags = new TestTagsController();
		$this->Tags->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tags);

		parent::tearDown();
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {

	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {

	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}

}

<?php
App::uses('CollectionsController', 'Controller');

/**
 * TestCollectionsController *
 */
class TestCollectionsController extends CollectionsController {
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
 * CollectionsController Test Case
 *
 */
class CollectionsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.collection', 'app.membership', 'app.resource', 'app.user', 'app.tag', 'app.comment', 'app.bookmark', 'app.hotspot');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Collections = new TestCollectionsController();
		$this->Collections->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Collections);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {

	}

/**
 * testCreate method
 *
 * @return void
 */
	public function testCreate() {

	}

/**
 * testAddMember method
 *
 * @return void
 */
	public function testAddMember() {

	}

/**
 * testUpdate method
 *
 * @return void
 */
	public function testUpdate() {

	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {

	}

/**
 * testResource method
 *
 * @return void
 */
	public function testResource() {

	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}

}

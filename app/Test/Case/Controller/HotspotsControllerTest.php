<?php
App::uses('Controller', 'Controller');
App::uses('HotspotsController', 'Controller');

/**
 * TestHotspotsController *
 */
class TestHotspotsController extends HotspotsController {
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
 * HotspotsController Test Case
 *
 */
class HotspotsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.hotspot', 'app.resource', 'app.user', 'app.tag', 'app.comment', 'app.bookmark', 'app.collection', 'app.membership');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Hotspots = new TestHotspotsController();
		$this->Hotspots->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Hotspots);

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

<?php
App::uses('Controller', 'Controller');
App::uses('MembershipsController', 'Controller');

/**
 * TestMembershipsController *
 */
class TestMembershipsController extends MembershipsController {
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
 * MembershipsController Test Case
 *
 */
class MembershipsControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.membership', 'app.resource', 'app.user', 'app.tag', 'app.comment', 'app.bookmark', 'app.collection', 'app.hotspot');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Memberships = new TestMembershipsController();
		$this->Memberships->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Memberships);

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
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {

	}

}

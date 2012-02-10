<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.user', 'app.resource', 'app.membership', 'app.collection', 'app.comment', 'app.tag', 'app.hotspot', 'app.bookmark');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

/**
 * testFindByRef method
 *
 * @return void
 */
	public function testFindByRef() {

	}

/**
 * testGetIDbyName method
 *
 * @return void
 */
	public function testGetIDbyName() {

	}

}

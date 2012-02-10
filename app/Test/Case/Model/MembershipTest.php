<?php
App::uses('Membership', 'Model');

/**
 * Membership Test Case
 *
 */
class MembershipTestCase extends CakeTestCase {
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

		$this->Membership = ClassRegistry::init('Membership');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Membership);

		parent::tearDown();
	}

}

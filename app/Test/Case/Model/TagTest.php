<?php
App::uses('Tag', 'Model');

/**
 * Tag Test Case
 *
 */
class TagTestCase extends CakeTestCase {
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

		$this->Tag = ClassRegistry::init('Tag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Tag);

		parent::tearDown();
	}

}

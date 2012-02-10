<?php
App::uses('Collection', 'Model');

/**
 * Collection Test Case
 *
 */
class CollectionTestCase extends CakeTestCase {
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

		$this->Collection = ClassRegistry::init('Collection');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Collection);

		parent::tearDown();
	}

/**
 * testGetResource method
 *
 * @return void
 */
	public function testGetResource() {

	}

}

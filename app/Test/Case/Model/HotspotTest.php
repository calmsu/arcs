<?php
App::uses('Hotspot', 'Model');

/**
 * Hotspot Test Case
 *
 */
class HotspotTestCase extends CakeTestCase {
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

		$this->Hotspot = ClassRegistry::init('Hotspot');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Hotspot);

		parent::tearDown();
	}

}

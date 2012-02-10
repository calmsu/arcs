<?php
App::uses('Resource', 'Model');

/**
 * Resource Test Case
 *
 */
class ResourceTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.resource', 'app.user', 'app.tag', 'app.comment', 'app.bookmark', 'app.collection', 'app.membership', 'app.hotspot');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Resource = ClassRegistry::init('Resource');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Resource);

		parent::tearDown();
	}

/**
 * testIsDoc method
 *
 * @return void
 */
	public function testIsDoc() {

	}

/**
 * testGetSHA method
 *
 * @return void
 */
	public function testGetSHA() {

	}

/**
 * testGetPath method
 *
 * @return void
 */
	public function testGetPath() {

	}

/**
 * testGetURL method
 *
 * @return void
 */
	public function testGetURL() {

	}

/**
 * testSetDefaultThumb method
 *
 * @return void
 */
	public function testSetDefaultThumb() {

	}

/**
 * testSearch method
 *
 * @return void
 */
	public function testSearch() {

	}

}

<?php
App::uses('Bookmark', 'Model');

/**
 * Bookmark Test Case
 *
 */
class BookmarkTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.bookmark', 'app.user', 'app.resource', 'app.membership', 'app.collection', 'app.comment', 'app.tag', 'app.hotspot');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Bookmark = ClassRegistry::init('Bookmark');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Bookmark);

		parent::tearDown();
	}

}

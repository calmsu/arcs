<?php
App::uses('Controller', 'Controller');
App::uses('ResourcesController', 'Controller');

/**
 * TestResourcesController *
 */
class TestResourcesController extends ResourcesController {
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
 * ResourcesController Test Case
 *
 */
class ResourcesControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
    public $fixtures = array(
        'app.resource', 'app.user', 'app.tag', 'app.comment', 'app.bookmark', 
        'app.collection', 'app.membership', 'app.hotspot'
    );

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Resources = new TestResourcesController();
		$this->Resources->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Resources);

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
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
        $data = array(
            'Resource' => array()
        );
	}

/**
 * testPdfSplit method
 *
 * @return void
 */
	public function testPdfSplit() {

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
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}

/**
 * testSearch method
 *
 * @return void
 */
	public function testSearch() {

	}

/**
 * testFacetedSearch method
 *
 * @return void
 */
	public function testFacetedSearch() {

	}

/**
 * testComment method
 *
 * @return void
 */
	public function testComment() {

	}

/**
 * testTag method
 *
 * @return void
 */
	public function testTag() {

	}

/**
 * testHotspot method
 *
 * @return void
 */
	public function testHotspot() {

	}

}

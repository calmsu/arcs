<?php
App::uses('MetaResourcesController', 'Controller');

/**
 * TestMetaResourcesController *
 */
class TestMetaResourcesController extends MetaResourcesController {
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
 * MetaResourcesController Test Case
 *
 */
class MetaResourcesControllerTestCase extends ControllerTestCase {
    /**
     * Fixtures
     *
     * @var array
     */
	public $fixtures = array('app.meta_resource');

    /**
     * setUp method
     *
     * @return void
     */
	public function setUp() {
		parent::setUp();

		$this->MetaResources = new TestMetaResourcesController();
		$this->MetaResources->constructClasses();
	}

    /**
     * tearDown method
     *
     * @return void
     */
	public function tearDown() {
		unset($this->MetaResources);

		parent::tearDown();
	}

    /**
     * testAdd method
     *
     * @return void
     */
	public function testAdd() {
        try {
            $this->testAction('/meta_resources/add', array('method' => 'get'));
        } catch (MethodNotAllowedException $e) {
            return;
        }
        $this->fail('Exception not raised');
	}

    /**
     * testEdit method
     *
     * @return void
     */
	public function testEdit() {

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

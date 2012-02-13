<?php
class AllTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('All tests');
        $suite->addTestDirectory(TESTS . 'Case' . DS . 'Controller');
        $suite->addTestDirectory(TESTS . 'Case' . DS . 'Model');
        $suite->addTestDirectory(TESTS . 'Case' . DS . 'View');
        return $suite;
    }
}

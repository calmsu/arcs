<?php 
App::uses('AuthComponent', 'Controller/Component');
/**
 * Install Shell
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class InstallShell extends AppShell {

    public $uses = array('User');

    /**
     * Bootstrap a user.
     */
    public function main() {
        $this->User->permit('role');
        $this->User->add(array(
            'name' => 'Admin',
            'username' => 'admin',
            'password' => 'pass',
            'role' => 0
        ));
    }

    public function startup() {
    }
}

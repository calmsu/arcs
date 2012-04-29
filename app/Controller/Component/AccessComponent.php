<?php
/**
 * Access component
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class AccessComponent extends Component {

    public $components = array('Auth');

    public $roles = array(
        'Admin' => 0,
        'SrResearcher' => 1,
        'Researcher' => 2,
        'Unknown' => 3
    );

    public function __call($name, $arguments) {
        if (substr($name, 0, 2) == 'is') {
            if (isset($this->roles[substr($name, 2)]))
                return $this->is(substr($name, 2));
        } else {
            return call_user_func_array($this->$name, $arguments);
        }
    }

    public function is($role) {
        $roleVal = $this->roles[$role];
        if (is_null($this->Auth->user())) return false;
        return $this->Auth->user('role') <= $roleVal;
    }

    public function isCreator($object, $key=null) {
        if ($key) {
            $id = $object[$key];
        } else if (isset($object['user_id'])) {
            $id = $object['user_id'];
        } else {
            return false;
        }
        return $this->Auth->user('id') == $id;
    }
}

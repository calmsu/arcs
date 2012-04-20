<?php
/**
 * User model
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class User extends AppModel {

    public $name = 'User';

    public $hasMany = array(
        'Resource', 
        'Keyword', 
        'Comment',
        'Bookmark',
        'Hotspot',
        'Collection'
    );

    public $whitelist = array(
        'name', 'email', 'username', 'password'
    );

    public $actsAs = array('Containable');

    /**
     * Don't give out the user's hashed password to non-primary finds. 
     */
    function afterFind($results, $primary) {
        $results = parent::afterFind($results, $primary);
        if (!$primary) {
            $results = $this->resultsMap($results, function($r) {
                $r['password'] = '****';
                return $r;
            });
        }
        $results = $this->resultsMap($results, function($r) {
            if (isset($r['email']))
                $r['gravatar'] = md5(strtolower($r['email']));
            return $r;
        });
        return $results;
    }

    /**
     * (Try to) find a user given a reference, which may be the
     * id or username.
     *
     * @param string ref   id or username
     * @return array       user array
     */
    function findByRef($ref) {
        $user = $this->findById($ref);
        if (!$user) $user = $this->findByUsername($ref);
        return $user;
    }

    /**
     * Hash the password before saving it.
     */
    function beforeSave() {
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password(
                $this->data['User']['password']
            );
        }
    }
}

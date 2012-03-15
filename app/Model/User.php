<?php

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

    /**
     * Don't give out the user's hashed password to non-primary finds. 
     */
    function afterFind($results, $primary) {
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
     * Return true if the given user is allowed to create Meta-resources
     * for the given resource.
     *
     * @param user
     * @param resource
     */
    function canMakeMeta($user, $resource) {
        if (!$resource['Resource']['exclusive']) return true;
        return false;
    }

    /** 
     * Return true if the given user is allowed to resolve Flags for
     * the given resource.
     *
     * @param user
     * @param resource
     */
    function canResolveFlag($user, $resource) {
        if ($user['User']['role'] <= 1) return true;
        return false;
    }

    /**
     * (Try to) find a user given a reference, which may be the
     * id or username.
     *
     * @param ref   id or username
     */
    function findByRef($ref) {
        $user = $this->findById($ref);
        if (!$user) $user = $this->findByUsername($ref);
        return $user;
    }

    /**
     * Return the id, as a string, of the user with the given name.
     * If two users with the same name exist, you only get the first
     * one.
     */
    function getIDbyName($name) {
        $user = $this->findByName($name);
        return $user['User']['name'];
    }

    /**
     * Hash the password before saving it.
     */
    function beforeSave() {
        $this->data['User']['password'] = AuthComponent::password(
            $this->data['User']['password']
        );
    }
}

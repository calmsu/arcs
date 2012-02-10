<?php
class AppModel extends Model {
    
    /**
     * For use in a model's afterFind() method, to collapse
     * the results. In our case the client-side code expects
     *
     * For example, this:
     *
     *     array(
     *        'Resource' => array(
     *            'id' => 'abcdef0123456789',
     *            'thumb' => 'http://www...'
     *        ),
     *        'User' => array(
     *            'id' => 'abcdef0123456789',
     *            'username' => 'ndreynolds'
     *        )
     *      );
     *
     * Becomes this:
     *     
     *     array(
     *         'id' => 'abcdef0123456789',
     *         'thumb' => 'http://www...',
     *         'user_id' => 'abcdef0123456789',
     *         'user_username' => 'ndreynolds',
     *     );
     *
     * @param results
     */
    public function collapseResults($results) {
    }
}

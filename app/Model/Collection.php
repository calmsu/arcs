<?php
/**
 * Collection model
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class Collection extends AppModel {
    public $name = 'Collection';
    public $hasMany = array('Membership');
    public $whitelist = array(
        'title', 'description', 'public', 'pdf', 'temporary'
    ); 
}

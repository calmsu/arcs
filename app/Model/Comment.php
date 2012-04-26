<?php
/**
 * Comment model
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class Comment extends AppModel {
    public $name = 'Comment';
    public $belongsTo = array('User', 'Resource');
    public $whitelist = array('resource_id', 'content');
}

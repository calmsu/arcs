<?php
/**
 * Bookmark model
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class Bookmark extends AppModel {
    public $name = 'Bookmark';
    public $belongsTo = array('User');
}

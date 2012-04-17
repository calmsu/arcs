<?php
/**
 * Flag model
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class Flag extends AppModel {
    public $name = 'Flag';
    public $belongsTo = array('User', 'Resource');
    public $whitelist = array('reason', 'explanation', 'resource_id');
}

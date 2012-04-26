<?php

namespace _;

/**
 * Useful functional programming constructs, ported from Underscore.js, and 
 * added as needed. Implementation uses closures, so PHP 5.3+.
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */


/**
 * Map over an two-dimensional array and return the value given by $key for
 * each sub-array.
 *
 * @param array $array
 * @param string $key
 */
function pluck($array, $key) {
    return array_map(function($sub) use ($key) { return $sub[$key]; }, $array);
}

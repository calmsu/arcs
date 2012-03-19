<?php

/**
 * @namespace
 */
namespace Relic;

/**
 * Shorter directory separator
 */ 
const DS = DIRECTORY_SEPARATOR;

/**
 * Shell dependencies
 */
$SHELL_DEPS = array(
    'ghostscript' => false,
    'pdfinfo' => false,
);

/**
 * PHP dependencies
 */
$PHP_DEPS = array(
    'Imagick' => false
);

if (php_sapi_name() == 'cli') {
    // Verify that each shell dependency is an executable in the user's path.
    foreach ($SHELL_DEPS as $dep => $avail) {
        $path = getenv('PATH');
        foreach (explode(':', $path) as $dir) {
            if (is_executable($dir . DS . $dep)) {
                $avail = true;
                break;
            }
        }
        if (!$avail)
            trigger_error("$dep not found. Attemping to continue.\n");
        $SHELL_DEPS[$dep] = $avail;
    }

    // Verify that each PHP dependency is an existing class or function.
    foreach ($PHP_DEPS as $dep => $avail) {
        if (class_exists($dep) || function_exists($dep)) {
            $PHP_DEPS[$dep] = true;
        } else {
            $PHP_DEPS[$dep] = false;
            trigger_error("$dep not found. Attemping to continue.\n");
        }
    }
}

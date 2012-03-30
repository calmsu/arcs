<?php
/**
 * @namespace
 */
namespace Relic;

require_once('Relic.php');
require_once('PDF.php');
require_once('Image.php');

/**
 * Usage information.
 */
const HELP_MSG = <<<HELP
Relic is a media processing library. It reads EXIF data,
splits PDFs into images, reads MIME types, and makes 
thumbnails.

Usage:
  relic COMMAND [OPTIONS]

Commands:
  thumb [OPTIONS] [image]
  split [OPTIONS] [pdf] [dst]
  metadata [OPTIONS] [file]
  mime [OPTIONS] [file]

Dependencies:
  - Ghostscript
  - ImageMagick

HELP;

/**
 * The CommandLine class implements a command line interface to the Relic 
 * library.
 *
 * It provides a simple argument parser, prints usage information, and 
 * delegates to other Relic components.
 *
 * @package   Relic
 */
class CommandLine {

    /**
     * Stores each subcommand's configuration. 
     *
     * Option syntax is borrowed from the Zend Getopt implentation. The option
     * name and any aliases are separated with the pipe symbol. One character 
     * aliases are short options, anything longer is a 'long' option. The 
     * option type is given as 's', 'i', or 'b' (string, integer, or boolean),
     * prefixed by an equal sign. If not type is given, we assume it's a string.
     * Options with a boolean type are marked true if present--they do not take
     * a value. All other options require a value. Each option array value is a
     * description of that option.
     *
     * Parameters are given as :name => :description pairs. Each parameter is
     * required.
     */
    public $commands = array(
        'thumb' => array(
            'help' => 'Creates a thumbnail given an image.',
            'options' => array(
                'width|w=i' => 'Set width, best fit when used with height',
                'height|h=i' => 'Set height, best fit when used with width',
                'format|f=i' => 'Set format (e.g. png, jpeg)'
            ),
            'params' => array(
                'image' => 'Path to the source image',
                'dst' => 'Path to write the thumbnail to'
            )
        ),
        'split' => array(
            'help' => 'Splits a PDF into images.',
            'options' => array(
                'basename|b' => 'Images are saved as [basename]-p[page].[ext]',
                'resolution|r=i' => 'Set the page resolution',
                'page|p=i' => 'Extract a specific page',
                'format|f' => 'Set the format for extracted images (e.g. png, jpeg)',
                'verbose|v=b' => 'Show progress information'
            ),
            'params' => array(
                'pdf' => 'Path to the PDF to split',
                'dst' => 'Directory in which extracted images will be saved'
            )
        ),
        'metadata' => array(
            'help' => 'Extracts metadata from a file.',
            'options' => array(),
            'params' => array(
                'file' => 'Path to a file'
            )
        ),
        'mime' => array(
            'help' => 'Reads the MIME type from a file.',
            'options' => array(
                'charset|c' => 'Include charset information.'
            ),
            'params' => array(
                'file' => 'Path to a file'
            )
        )
    );
    
    /**
     * Constructor
     *
     * Parses arguments, looks for a command, and hands off command
     * options to another Relic library function.
     */
    public function __construct() {
        $this->_parseArgs();
        if (array_key_exists($this->command, $this->commands)) {
            $args = $this->_parseOpts(
                $this->commands[$this->command]['options'],
                $this->commands[$this->command]['params']
            );
            switch ($this->command) {
                case 'thumb':
                    Image::thumbnail(
                        $args['params']['image'],
                        $args['params']['dst'],
                        $args['options']
                    );
                    break;
                case 'split':
                    PDF::split($args);
                    break;
                case 'metadata':
                    $mime = Mime::mime($args['params']['file']);
                    if (in_array($mime, array('image/jpg', 
                                              'image/jpeg', 
                                              'image/tiff'))) {
                        $image = new Image($args['params']['file']);
                        $this->prettyPrint($image->exif());
                    } else if ($mime == 'application/pdf') {
                        $pdf = new PDF($args['params']['file']);
                        $this->prettyPrint($pdf->info);
                    }
                    break;
                case 'mime':
                    Mime::printMime($args['params']['file']);
                    break;
            }
        } else {
            $this->_usage(false, 'Unknown command.');
            exit(1);
        }
    }
  
    /**
     * Format and print the given array.
     *
     * @param  array $array
     * @param  depth $depth
     * @return void
     */
    public function prettyPrint($array, $depth=0) {
        $width = 0;
        foreach ($array as $k => $v)
            $width = strlen($k) > $width ? strlen($k) + 1 : $width;
        $indent = $depth * 2;
        foreach ($array as $k => $v) {
            echo str_repeat(' ', $indent);
            if (is_array($v)) {
                echo "$k:\n";
                $this->prettyPrint($v, $depth + 1);
            } else {
                printf("%-{$width}s  %s\n", $k . ':', $v);
            }
        }
    }

    /**
     * Displays usage information, optionally with a preceding error message.
     *
     * @param  mixed $command
     * @param  string $error
     * @return void
     */
    protected function _usage($command=false, $error='') {
        if ($error) 
            printf("Error: %s\n%s\n", $error, str_repeat('-', 79));
        if (!$command) {
            echo HELP_MSG;
        } else {
            $commandConfig = $this->commands[$command];
            $params = array_map(function($p) {
                return "[$p]";
            }, array_keys($commandConfig['params']));
            $params = implode(' ', $params);
            printf("Usage: relic %s [OPTIONS] %s\n\nHelp: %s\n", 
                $command, $params, $commandConfig['help']
            );
            echo "\nParameters:\n";
            foreach ($commandConfig['params'] as $param => $help) {
                printf("  %-18s %s\n", $param, $help);
            }
            echo "\nOptions:\n";
            foreach ($commandConfig['options'] as $opt => $help) {
                $info = $this->_splitOpts($opt);
                $longs = array_map(function($l) { return "--$l"; }, $info['long']);
                $shorts = array_map(function($s) { return "-$s"; }, $info['short']);
                $all = implode(', ', array_merge($longs, $shorts));
                printf("  %-18s %s\n", $all, $help);
            }
        }
    }

    /**
     * Parses the raw $argv global into a subcommand and an options array.
     *
     * @return void
     */
    protected function _parseArgs() {
        $this->argv = array_slice($GLOBALS['argv'], 1);
        if (!isset($this->argv[0])) {
            $this->_usage();
            exit(1);
        }
        $this->command = $this->argv[0];
        $this->options = array_slice($this->argv, 1);
    }

    /**
     * Parses command options.
     *
     * @param  array $options
     * @param  array $params
     * @return array parsed command options and parameters
     */
    protected function _parseOpts($options, $params) {
        $argv = $this->options;
        $paramKeys = array_keys($params);
        $parsed = array(
            'options' => array(),
            'params' => array()
        );

        while (count($argv) > 0) {
            $cur = array_shift($argv);
            if (substr($cur, 0, 2) == '--') {
                $opt = substr($cur, 2);
            } else if ($cur[0] == '-') {
                $opt = substr($cur, 1);
            } else {
                $key = array_shift($paramKeys);
                $parsed['params'][$key] = $cur;
                continue;
            }
            $info = $this->_findOpt($opt, $options);
            $name = $info['name'];
            switch ($info['type']) {
                case 'b':
                    $parsed['options'][$name] = true;
                    break;
                case 'i':
                    $parsed['options'][$name] = intval(array_shift($argv));
                    break;
                case 's':
                    $parsed['options'][$name] = array_shift($argv);
                    break;
            }
        }
        if (count($paramKeys)) {
            $this->_usage($this->command, "Missing parameters.");
            exit(1);
        }
        return $parsed;
    }

    /**
     * Finds an option within an options configuration array.
     *
     * @param string $opt
     * @param array $options
     * @return array
     */
    protected function _findOpt($opt, $options) {
        foreach ($options as $key => $help) {
            $optInfo = $this->_splitOpts($key);
            if (in_array($opt, $optInfo['aliases'])) {
                return $optInfo;
            }
        }
        $this->_usage($this->command, "Option '$opt' not recognized.");
        exit(1);
    }

    /**
     * Splits and sorts formatted option string into its components. 
     *
     * Provides the option name (the first alias), aliases, long-style aliases,
     * short-style aliases, and the option type, as members of the return array.
     *
     * @param  string $opts
     * @return array
     */
    protected function _splitOpts($opt) {
        $aliases = explode('|', array_shift(explode('=', $opt)));
        return array(
            'aliases' => $aliases,
            'name' => $aliases[0],
            'short' => array_filter($aliases, function($a) { 
                return strlen($a) == 1; 
            }),
            'long' => array_filter($aliases, function($a) { 
                return strlen($a) > 1; 
            }),
            'type' => strstr($opt, '=') ? array_pop(explode('=', $opt)) : 's'
        );
    }
}

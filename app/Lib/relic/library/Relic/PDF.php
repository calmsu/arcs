<?php
/**
 * @namespace
 */
namespace Relic;

/**
 * Require the Relic and Mime scripts.
 */
require_once("Relic.php");
require_once("Mime.php");

/**
 * PDF class
 *
 * @package Relic
 */
class PDF {

    public $verbose = false;
    public $defaults = array(
        'resolution' => 0,
        'format' => 'jpeg',
        'device' => null,
        'basename' => null,
        'verbose' => false
    );

    /**
     * Constructor
     *
     * @param string $path file path to a PDF.
     */
    public function __construct($path, $options=array()) {
        # Make sure it exists and is readable.
        if (!is_file($path) || !is_readable($path))
            throw new \Exception('File does not exist, or could not be read.');

        # Make sure it's a PDF.
        $mtype = Mime::mime($path);
        if ($mtype != 'application/pdf' && $mtype != 'application/x-pdf')
            throw new \Exception('File is not a PDF.');

        $this->path = $path;
        $this->pathinfo = pathinfo($path);
        $this->info = $this->getInfo();
        $this->npages = $this->info['Pages'];

        $this->options = array_merge($this->defaults, $options);
        if ($this->options['verbose']) $this->verbose = true;
        if (is_null($this->options['basename']))
            $this->options['basename'] = $this->pathinfo['filename'];
        if ($this->options['resolution'] == 0)
            $this->options['resolution'] = $this->getResolution();
    }

    /**
     * @param  array $args
     * @return bool
     */
    public static function split($args) {
        $pdf = new static($args['params']['pdf'], $args['options']);

        if (isset($args['options']['page']))
            return $pdf->extractPage(
                $args['options']['page'], 
                $args['params']['dst'], 
                $args['options']
            );
        else
            return $pdf->extractPages(
                $args['params']['dst'], 
                $args['options']
            );
    }
    
    /**
     * Extracts a page by number from the PDF and writes it to disk
     * as a JPEG.
     *
     * @param  integer $n    page number, first page is page 1.
     *
     * @param  string $dst   destination path for the JPEG. If this is a 
     *                       directory, we'll save it within the directory
     *                       and base the filename on the source file and 
     *                       append the page number. Otherwise, we'll write
     *                       it to the given path.
     *
     * @return bool          result of the `_extractPage` method.
     */
    public function extractPage($n, $dst) {
        if (is_dir($dst)) {
            $dst = rtrim($dst, DS);
            $dst = $dst . DS . $this->options['basename'] . 
                "-p{$n}.{$this->options['format']}";
        }
        return $this->_extractPage($n, $this->path, $dst);
    }

    /**
     * Extracts all of pages from a PDF and writes them as JPEGs in the
     * given destination directory.
     *
     * @param  string $dst       an existing directory
     * @param  string $basename  string to base the filenames on. We'll append
     *                           "-pN.jpeg" to the basename. By default this will
     *                           be the source file, with its extension stripped.
     * @param  integer $res      by default we'll auto-detect the page resolution
     *                           set the $res paramter to override this.
     * @return void
     */
    public function extractPages($dst) {
        $dst = rtrim($dst, DS);
        for ($page=1; $page<=$this->npages; $page++) {
            $dpath = $dst . DS . $this->options['basename'] . 
                "-p{$page}.{$this->options['format']}";
            $this->_extractPage($page, $this->path, $dpath);
        }
    }

    /**
     * Buld and run the `ghostscript` command to do the actual processing.
     *
     * @param  integer $n   page to process as an int
     * @param  string $src  source path of the PDF
     * @param  string $dst  destination path of the JPEG
     * @return bool         command output
     */
    protected function _extractPage($n, $src, $dst) {
        if (is_null($this->options['device'])) {
            $devices = array(
                'png' => 'png256',
                'jpeg' => 'jpeg',
                'tiff' => 'tiffg4'
            );
            $device = $devices[$this->options['format']];
        } else {
            $device = $this->options['device'];
        }

        # Build the command.
        $command = 'ghostscript' .
            ' -dBATCH -r' . $this->options['resolution'] .
            ' -dUseCIEColor' .
            ' -dNOPAUSE' .
            ' -sDEVICE=' . $device .
            ' -sOutputFile=' . escapeshellarg($dst) .
            ' -dFirstPage=' . $n .
            ' -dLastPage=' . $n .
            ' -dEPSCrop ' . escapeshellarg($src); 

        # Output a debug message.
        if ($this->verbose)
            printf("page:%d/%d, dest:'%s', resolution:%d dpi\n",
                $n, $this->npages, $dst, $this->options['resolution']);

        return is_null(shell_exec($command)) ? false : true;
    }

    /**
     * Determines the page resolution of the pdf.
     *
     * @return integer  resolution
     */
    public function getResolution() {
        # First we gotta get the page size
        $pageSize = $this->info['Page size'];

        # pdfinfo output looks like this: '123.456 X 789.123 pts'
        $pageSize = explode(' ', $pageSize);

        # This means the height and width should now be at index 0 and 2
        $width  = trim($pageSize[0]);
        $height = trim($pageSize[2]);

        # Max dimension is just the larger of the two.
        if ($width > $height) $maxDim = $width;
        else $maxDim = $height;

        # Determine and return appropriate resolution
        return ceil(1768/($maxDim/72));
    }

    /**
     * Runs the `pdfinfo` command and parses the output into an array.
     *
     * @return array  the keys matching the defined pdfinfo utput.
     */
    public function getInfo() {
        $command = 'pdfinfo ' . escapeshellarg($this->path);
        $output  = shell_exec($command);
        $output  = explode("\n", $output);
        $info = array();
        foreach ($output as $line) {
            $line = explode(":", $line);
            if (count($line) == 2) {
                $info[$line[0]] = trim($line[1]);
            }
        }
        return $info;
    }
}

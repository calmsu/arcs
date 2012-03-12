<?php

require_once('Mime.php');

class PdfExtractor {

    public function __construct($path) {
        # Make sure it exists and is readable.
        if (!is_file($path) || !is_readable($path))
            throw new Exception('File does not exist, or could not be read.');

        # Make sure it's a PDF.
        $mtype = Mime::getMime($path);
        if ($mtype != 'application/pdf' && $mtype != 'application/x-pdf')
            throw new Exception('File is not a PDF.');

        # Set some properties from the pathinfo call.
        $parts = $pathinfo($path);
        $this->path = $path;
        $this->basename = $parts['basename'];
        $this->extension = $parts['extension'];
        $this->ds = DIRECTORY_SEPARATOR;
        $this->info = $this->getInfo();
        $this->npages = $this->info['Pages'];
    }
    
    /**
     * Extracts a page by number from the PDF and writes it to disk
     * as a JPEG.
     *
     * @param n     page number, first page is page 1.
     * @param dst   destination path for the JPEG. If this is a 
     *              directory, we'll save it within the directory
     *              and base the filename on the source file and 
     *              append the page number. Otherwise, we'll write
     *              it to the given path.
     */
    public function extractPage($n, $dst) {
        if (is_dir($dst)) {
            $dst = rtrim($dst, $this->ds);
            $dst = $dst . $this->ds . $this->basename . "-p{$page}.jpeg";
        }
        return $this->_extractPage($n, $this->path, $dst);
    }

    /**
     * Extracts all of pages from a PDF and writes them as JPEGs in the
     * given destination directory.
     *
     * @param dst       an existing directory
     * @param basename  string to base the filenames on. We'll append
     *                  "-pN.jpeg" to the basename. By default this will
     *                  be the source file, with its extension stripped.
     */
    public function extractPages($dst, $basename=null) {
        $basename = is_string($basename) ? $basename : $this->basename;
        $dst = rtrim($dst, $this->ds);
        $info = $this->getInfo();
        $npages = $info['Pages'];
        for ($page=1; $page<=$npages; $page++) {
            $dpath = $dst . $this->ds . $basename . "-p{$page}.jpeg";
            $this->_extractPage($page, $this->path, $dpath);
        }
    }

    /**
     * Buld and run the `ghostscript` command to do the actual processing.
     *
     * @param n      page to process as an int
     * @param src    source path of the PDF
     * @param dst    destination path of the JPEG
     * @return       command output
     */
    private function _extractPage($n, $src, $dst) {
        # Grab the resolution.
        $resolution = $this->getResolution();

        # Build the command.
        $command = 'ghostscript';
        $options =  ' -dBATCH -r' . $resolution;
        $options .= ' -dUseCIEColor';
        $options .= ' -dNOPAUSE';
        $options .= ' -sDEVICE=jpeg';
        $options .= ' -sOutputFile=' . escapeshellarg($dst);
        $options .= ' -dFirstPage=' . $n;
        $options .= ' -dLastPage=' . $n;
        $options .= ' -dEPSCrop ' . escapeshellarg($src);
        return shell_exec($command . $options);
    }

    /**
     * Determines the page resolution of the pdf.
     *
     * @return   resolution as an integer.
     */
    public function getResolution() {
        # First we gotta get the page size
        $page_size = $this->info['Page size'];
        # pdfinfo output looks like this: '123.456 X 789.123 pts'
        $page_size = explode(' ', $pageSize);
        # This means the height and width should now be at index 0 and 2
        $width  = trim($pageSize[0]);
        $height = trim($pageSize[2]);
        # Max dimension is just the larger of the two.
        if ($width > $height) {
            $max_dim = $width;
        }
        else {
            $max_dim = $height;
        }
        # Determine and return appropriate resolution
        return ceil(1768/($max_dim/72));
    }

    /**
     * Runs the `pdfinfo` command and parses the output into an array.
     *
     * @return    an array with keys matching the defined pdfinfo
     *            output.
     */
    public function getInfo() {
        $command = 'pdfinfo ' . escapeshellarg($path);
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

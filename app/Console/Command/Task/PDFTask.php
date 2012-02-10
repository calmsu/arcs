<?php
class PDFTask extends AppShell {
    /**
     * Buld and run the `ghostscript` command to do the actual processing.
     *
     * @param page              page to process as an int
     * @param src               source path of the PDF
     * @param dst               destination path of the JPEG
     * @param resolution        resolution as an int (from get_page_resolution)
     * @return                  command output
     */
    public function processPage($page, $src, $dst, $resolution) {
        $ghostscript_path = Configure::read('ghostscript_path');
        $command = $ghostscript_path;
        $options =  ' -dBATCH -r' . $resolution;
        $options .= ' -dUseCIEColor';
        $options .= ' -dNOPAUSE';
        $options .= ' -sDEVICE=jpeg';
        $options .= ' -sOutputFile=' . escapeshellarg($dst);
        $options .= ' -dFirstPage=' . $page;
        $options .= ' -dLastPage=' . $page;
        $options .= ' -dEPSCrop ' . escapeshellarg($src);
        return shell_exec($command . $options);
    }

    /**
     * Get the page resolution given the pdfinfo array.
     *
     * @param pdfinfo       the array returned by the pdf_info function.
     * @return              resolution as an integer.
     */
    public function getPageResolution($pdfinfo) {
        # First we gotta get the page size
        $page_size = $pdfinfo['Page size'];
        # pdfinfo output looks like this: '123.456 X 789.123 pts'
        $page_size = explode(' ', $page_size);
        # This means the height and width should now be at index 0 and 2
        $width = trim($page_size[0]);
        $height = trim($page_size[2]);
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
     * Run the `pdfinfo` command and parse the output into an array.
     *
     * @param path        the path to the pdf file
     * @return            an array with keys matching the defined pdfinfo
     *                    output.
     */
    public function getInfo($path) {
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

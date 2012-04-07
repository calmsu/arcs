<?php

include_once(LIB . 'relic' . DS . 'library' . DS . 'PDF.php');

class SplitPdfTask extends AppShell {
    public $uses = array('Resource', 'Membership');

    public function execute($data) {
        $this->_loadModels();
        $id = $data['resource_id'];
        $collection_id = $data['collection_id'];
        $resource = $this->Resource->findById($id);
        $resource = $resource['Resource'];

        # Get and set its path.
        $path = $this->Resource->path($resource['sha'], $resource['file_name']);

        $pdf = new \Relic\PDF($path);

        # For each page in the PDF:
        for ($page = 1; $page <= $pdf->npages; $page++) {
            # Create a tmp file to write to.
            $tmp_file = tempnam(sys_get_temp_dir(), 'ARCS');
            # Extract the page.
            $pdf->extractPage($page, $tmp_file);

            # The name is just the PDF file name plus "-pX.jpeg"
            $basename = str_ireplace('.pdf', '', $resource['file_name']);
            $fname = $basename . "-p$page.jpeg";
            # Create the resource file.
            $sha = $this->Resource->createFile($tmp_file, array(
                'filename' => $fname, 
                'thumb' => true
            ));

            $this->Resource->permit('sha', 'file_size', 'file_name', 'user_id');
            # Save the resource.
            $this->Resource->add(array(
                'sha' => $sha,
                'title' => $resource['title'] . "-p$page",
                'public' => $resource['public'],
                'context' => $collection_id,
                'file_name' => $fname,
                'file_size' => $this->Resource->size($sha, $fname),
                'mime_type' => 'image/jpeg',
                'user_id' => $resource['user_id']
            ));

            # Save the collection membership.
            $this->Membership->pair($this->Resource->id, $collection_id);

            # Reset the Resource and Membership models for the next round.
            $this->Resource->create();
            $this->Membership->create();
        }
    }
}

<?php
include_once(LIB . 'Relic' . DS . 'Library' . DS . 'PDF.php');
/**
 * SplitPdf Task
 *
 * Split a PDF resource into multiple pages.
 * 
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class SplitPdfTask extends AppShell {
    public $uses = array('Resource', 'Membership');

    public function execute($data) {
        $this->_loadModels();
        $id = $data['resource_id'];
        $collection_id = $data['collection_id'];
        $resource = $this->Resource->findById($id);
        $metadata = $resource['Metadatum'];
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
            $padded_page = str_pad($page, 3, '0', STR_PAD_LEFT);
            $fname = $basename . "-p{$padded_page}.jpeg";
            # Create the resource file.
            $sha = $this->Resource->createFile($tmp_file, array(
                'filename' => $fname, 
                'thumb' => true
            ));

            $this->Resource->permit('sha', 'file_size', 'file_name', 'user_id');
            # Save the resource.
            $this->Resource->add(array(
                'sha' => $sha,
                'title' => $resource['title'] . "-p{$padded_page}",
                'public' => $resource['public'],
                'context' => $collection_id,
                'file_name' => $fname,
                'file_size' => $this->Resource->size($sha, $fname),
                'mime_type' => 'image/jpeg',
                'type' => isset($data['type']) ? $data['type'] : null,
                'user_id' => $resource['user_id']
            ));

            # Map the PDF's metadata on to the page.
            $this->mapMetadata($this->Resource->id, $metadata);

            # Save the collection membership.
            $this->Membership->pair($this->Resource->id, $collection_id, $page);

            # Reset the Resource and Membership models for the next round.
            $this->Resource->create();
            $this->Membership->create();
        }
    }

    protected function mapMetadata($rid, $metadata) {
        foreach($metadata as $m) {
            $this->Resource->Metadatum->store($rid, $m['attribute'], $m['value']);
        }
    }
}

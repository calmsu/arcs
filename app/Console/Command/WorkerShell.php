<?php

include_once(APPLIBS . 'ImageUtility.php');
include_once(APPLIBS . 'PdfExtractor.php');

class WorkerShell extends AppShell {

    public $uses = array('Task', 'Resource', 'Membership');

    /**
     * Checks the queue and delegates tasks.
     */
    public function main() {
        while (true) {
            # Find a new task...
            $task = $this->Task->pop();
            # ...or die if there aren't any.
            if (!$task) return $this->out('Nothing to do.');
            
            # Start the task.
            $this->Task->start($task['id']);
            $this->out("Task: {$task['id']} ({$task['job']})");

            # Delegate the job to a method.
            switch ($task['job']) {
                case "thumb":
                    $success = $this->thumb($task['resource_id']);
                    break;
                case "split_pdf":
                    $success = $this->split_pdf($task['resource_id'], $task['data']);
                    break;
            }

            # Mark it done.
            $this->Task->done($task['id'], $success ? 0 : 2);
            $success ? $this->out('Done.') : $this->out('Failed.');
        }
    }

    /**
     * Makes a thumbnail for a given resource.
     *
     * @param id
     */
    public function thumb($id) {
        $resource = $this->Resource->findById($id);
        return $this->Resource->makeThumbnail($resource['Resource']['sha']);
    }

    /**
     * Converts a single PDF resource into a collection of resources 
     * (as JPEGs)--one for each page of the PDF.
     *
     * @param id              resource id of the PDF
     * @param collection_id   id of the collection to fill. (This method
     *                        doesn't create one.)
     */
    public function split_pdf($id, $collection_id) {
        # Find the PDF resource.
        $result = $this->Resource->findById($id);
        $resource = $result['Resource'];

        # Get and set its path.
        $path = $this->Resource->path($resource['sha'], $resource['file_name']);

        $pdf = new PdfExtractor($path);

        # For each page in the PDF:
        for ($page=1; $page<=$pdf->npages; $page++) {
            # Output our progress.
            $this->out("$page/{$pdf->npages}");

            # Create a tmp file to write to.
            $tmp_file = tempnam(sys_get_temp_dir(), 'ARCS');
            # Extract the page.
            $pdf->extractPage($page, $tmp_file);

            # The name is just the PDF file name plus "-pX.jpeg"
            $basename = str_ireplace('.pdf', $resource['file_name']);
            $fname = $basename . "-p$page.jpeg";
            # Create the resource file.
            $sha = $this->Resource->createFile($tmp_file, $fname);

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
            # Make a thumbnail for it.
            $this->Resource->makeThumbnail($sha);
            # Reset the Resource and Membership models for the next round.
            $this->Resource->create();
            $this->Membership->create();
        }
        return true;
    }

    /**
     * Prints out the startup message.
     */
    public function startup() {
        $this->out();
        $this->out('ARCS Worker');
        $this->hr();
        $this->out(date('r'));
        $this->hr();
    }
}

<?php 

include_once(APPLIBS . 'relic' . DS . 'library' . DS . 'Relic' . DS . 'Image.php');
include_once(APPLIBS . 'relic' . DS . 'library' . DS . 'Relic' . DS . 'PDF.php');

class WorkerShell extends AppShell {

    public $uses = array('Task', 'Resource', 'Membership');

    /**
     * Checks the queue and delegates tasks.
     *
     * @return void
     */
    public function main() {
        while (true) {
            # Find a new task...
            $task = $this->Task->pop();
            # ...or die if there aren't any.
            if (!$task) return $this->out('Nothing to do.');
            
            # Start the task. (Marks it in-progress.)
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

    public function redo($id=null) {
        $id = is_null($id) ? $this->args[0] : $id;
        $this->Task->read(null, $id);
        $this->Task->set('status', 1);
        $this->Task->save();
    }

    /**
     * Makes a thumbnail for a given resource.
     *
     * @param id
     */
    public function thumb($id=null) {
        $id = is_null($id) ? $this->args[0] : $id;
        $resource = $this->Resource->findById($id);
        if (!$resource) return false;
        return $this->Resource->makeThumbnail($resource['Resource']['sha']);
    }

    public function zip($ids=null) {
        $ids = is_null($ids) ? $this->args : $ids;
        $resources = $this->Resource->find('all', array(
            'conditions' => array(
                'Resource.id' => $ids
            )
        ));
        $files = array();
        foreach ($resources as $r) {
            $files[$r['Resource']['file_name']] = $r['Resource']['sha'];
        }
        $this->out($this->Resource->makeZipfile($files));
    }

    /**
     * Converts a single PDF resource into a collection of resources 
     * (as JPEGs)--one for each page of the PDF.
     *
     * @param  id             resource id of the PDF
     * @param  collection_id  id of the collection to fill. (This method
     *                        doesn't create one.)
     * @return void
     */
    public function split_pdf($id, $collection_id) {
        # Find the PDF resource.
        $result = $this->Resource->findById($id);
        $resource = $result['Resource'];

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
            );

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

            # Output our progress.
            $this->out("$page/{$pdf->npages} $sha");

            # Reset the Resource and Membership models for the next round.
            $this->Resource->create();
            $this->Membership->create();
        }
        return true;
    }

    /**
     * Prints out the startup message.
     *
     * @return void
     */
    public function startup() {
        $this->out();
        $this->out('ARCS Worker');
        $this->hr();
        $this->out(date('r'));
        $this->hr();
    }
}

<?php

class WorkerShell extends AppShell {

    public $uses = array('Task', 'Resource', 'Membership');
    public $tasks = array('PDF');

    public function main() {
        # We look for open tasks each time the loop executes, so
        # it should be relatively thread-safe.
        
        while (true) {
            # Find a new task...
            $task = $this->Task->find('first', array(
                'conditions' => array(
                    'Task.status' => 1, 
                    'Task.in_progress' => false
            )));
            # ...or die.
            if (!$task) {
                return;
            }
            
            $task = $task['Task'];

            # Set task as in-progress.
            $this->Task->read(null, $task['id']);
            $this->Task->set('in_progress', true);
            $this->Task->save();

            # Debug.
            $this->out("Task: " .  $task['id'] .  " (" . $task['job'] . ")");

            # Delegate.
            $job = $task['job'];
            switch ($job) {
                case "thumb":
                    $success = $this->makeThumb($task['resource_id']);
                    break;
                case "pdf_split":
                    $success = $this->splitPDF($task['resource_id'], 
                                               $task['data']);
                    break;
            }

            # Debug that it's done.
            if ($success) {
                $this->out('Done.');
            }

            # Save the task return status
            $this->Task->set('status', ($success ? 0 : 2));
            $this->Task->set('in_progress', false);
            $this->Task->save();
        }
    }

    /**
     * Makes a thumbnail for a given resource, if possible.
     */
    public function makeThumb($id, $width=100, $height=100) {
        $resource = $this->Resource->findById($id);
        $resource = $resource['Resource'];

        $src = $this->Resource->path(
            $resource['sha'],
            $resource['file_name']
        );
        $dst = $this->Resource->path($resource['sha'], 'thumb.png');

        # If it's a PDF, add an index of 0 to the path.
        # ImageMagick will then make a thumb from the first page. 
        if ($resource['mime_type'] == 'application/pdf') {
            $src .= '[0]';
        }

        # Fire up an Imagick instance
		$imagick = new Imagick();
		try {
			$imagick->readimage($src);
		} catch (Exception $e){
            $this->out("Reading image failed.\n");
			return false; 
		}

        # Scale it.
		$imagick->setImageFormat('png');
        $imagick->scaleImage($width, $height, true);
		
        # Write it.
		if (!$imagick->writeImage($dst)) {
            $this->out("Writing thumbnail failed.\n");
			return false;
		}

        # Return successful.
        return true;
    }

    /**
     * Converts a single PDF resource into a collection of resources 
     * (as JPEGs)--one for each page of the PDF.
     *
     * @param id              resource id of the PDF
     * @param collection_id   id of the collection to fill. (This method
     *                        doesn't create one.)
     */
    public function splitPDF($id, $collection_id) {

        # Find the PDF resource.
        $resource_arr = $this->Resource->findById($id);
        $resource = $resource_arr['Resource'];

        # If it's not a PDF, return unsucessful.
        if (!($resource['mime_type'] == 'application/pdf')) return false;

        # Get and set its path.
        $resource['path'] = $this->Resource->path(
            $resource['sha'], 
            $resource['file_name']
        );

        # Get the page resolution.
        $pdfinfo = $this->PDF->getInfo($resource['path']);
        $resolution = $this->PDF->getPageResolution($pdfinfo);

        # For each page in the PDF:
        for ($page=1; $page<=$pdfinfo['Pages']; $page++) {

            # Output our progress
            $this->out("$page/{$pdfinfo['Pages']}");

            # Make a JPEG for the PDF page.
            #
            # We'll write it to a tmp file so we can later use the Resource
            # model's createFile method as if the file was uploaded.
            $tmp_file = tempnam(sys_get_temp_dir(), 'ARCS');
            $this->PDF->processPage(
                $page, // page number
                $resource['path'], // path to the PDF
                $tmp_file, // path to our tmp file
                $resolution // page resolution
            );

            # Move the file into place and get a SHA.
            #
            # The name is just the PDF file name plus "-pX.jpeg"
            $fname = rtrim($resource['file_name'], '.pdf') . "-p$page.jpeg";
            $sha = $this->Resource->createFile($tmp_file, $fname);

            # Save the resource.
            $this->Resource->save(array(
                'Resource' => array(
                    'sha' => $sha,
                    'title' => $resource['title'] . "-p$page",
                    'public' => $resource['public'],
                    'file_name' => $fname,
                    'file_size' => $this->Resource->size($sha, $fname),
                    'mime_type' => 'image/jpeg',
                    'user_id' => $resource['user_id']
            )));

            # Save the collection membership.
            $this->Membership->save(array(
                'Membership' => array(
                    'resource_id' => $this->Resource->id,
                    'collection_id' => $collection_id
            )));

            # Make a thumbnail for it.
            $this->makeThumb($this->Resource->id);

            # Reset the Resource and Membership models for the next round.
            $this->Resource->create();
            $this->Membership->create();
        }
        return true;
    }
}

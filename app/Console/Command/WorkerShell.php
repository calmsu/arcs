<?php

class WorkerShell extends AppShell {

    public $uses = array('Task', 'Resource', 'Membership');
    public $tasks = array('PDF');

    public function main() {
        # We look for open tasks each time the loop executes, so
        # it should be relatively thread-safe.
        
        while (true) {
            # Find a new task...
            $t = $this->Task->find('first', array(
                'conditions' => array(
                    'Task.status' => 1, 
                    'Task.in_progress' => false
            )));
            # ...or die.
            if (!$t) {
                return;
            }

            # Set task as in-progress.
            $this->Task->read(null, $t['Task']['id']);
            $this->Task->set('in_progress', true);
            $this->Task->save();

            # Debug.
            $this->out(
                "Task: " . 
                $t['Task']['id'] . 
                " (" . $t['Task']['job'] . ")"
            );

            # Delegate.
            $job = $t['Task']['job'];
            switch ($job) {
                case "thumb":
                    $status = $this->makeThumb($t['Task']['resource_id']);
                    break;
                case "pdf_split":
                    $status = $this->splitPDF($t['Task']['resource_id'], 
                                              $t['Task']['data']);
                    break;
            }

            # Debug that it's done.
            if ($status == 0) {
                $this->out('Done.');
            }

            # Save the task return status
            $this->Task->set('status', $status);
            $this->Task->set('in_progress', false);
            $this->Task->save();
        }
    }

    /**
     * Makes a thumbnail for a given resource, if possible.
     */
    public function makeThumb($id, $width=100, $height=100) {
        $resource = $this->Resource->findById($id);
        $sha = $resource['Resource']['sha'];
        $file_name = $resource['Resource']['file_name'];
        $file_path = $this->Resource->getPath($sha) . DS . $file_name;
        $thumb_path = $this->Resource->getPath($sha) . DS . 'thumb.png';
        $mime = $resource['Resource']['mime_type'];

        # If it's a PDF, add an index of 0 to the path.
        # ImageMagick will then make a thumb from the first page. 
        if ($mime == 'application/pdf') {
            $file_path .= '[0]';
        }

        # Fire up an Imagick instance
		$imagick = new Imagick();
		try {
			$imagick->readimage($file_path);
		}
		catch (Exception $e){
            $this->out("Reading image failed.\n");
			return 2;
		}

        # Scale it.
		$imagick->setImageFormat('png');
        $imagick->scaleImage($width, $height, true);
		
        # Write it.
		if (!$imagick->writeImage($thumb_path)) {
            $this->out("Writing thumbnail failed.\n");
			return 2;
		}

        # Return successful.
        return 0;
    }

    /**
     * Converts a single PDF resource into a collection of JPEGs--one for
     * each page.
     *
     * @param id              resource id of the PDF
     * @param collection_id   id of the collection to fill. (This method
     *                        doesn't create one.)
     */
    public function splitPDF($id, $collection_id) {
        # Get the PDF resource and its attributes
        $resource = $this->Resource->findById($id);
        $sha = $resource['Resource']['sha'];
        $file_name = $resource['Resource']['file_name'];
        $file_path = $this->Resource->getPath($sha) . DS . $file_name;

        # If it's not a PDF, return unsucessful.
        if (!($resource['Resource']['mime_type'] == 'application/pdf')) {
            return 2;
        }

        # Get info about the PDF file itself.
        $pdfinfo = $this->PDF->getInfo($file_path);
        $resolution = $this->PDF->getPageResolution($pdfinfo);
        $num_pages = $pdfinfo['Pages'];

        # For each page in the PDF:
        for($page=1; $page<=$num_pages; $page++) {
            $this->out("$page/$num_pages");
            # The name is just the PDF file name plus "-pX.jpeg"
            $file_name = rtrim($resource['Resource']['file_name'], '.pdf');
            $file_name .= "-p$page.jpeg";
            $title = $resource['Resource']['title'] . "-p$page";
            $sha = $this->Resource->getSHA($file_name);
            $path = $this->Resource->getPath($sha, true) . DS . $file_name;
            # Process the page to get a JPEG.
            $this->PDF->processPage($page, $file_path, $path, $resolution);
            $this->Resource->save(array(
                'Resource' => array(
                    'sha' => $sha,
                    'title' => $title,
                    'public' => $resource['Resource']['public'],
                    'file_name' => $file_name,
                    'mime_type' => 'image/jpeg',
                    'user_id' => $resource['Resource']['user_id']
            )));
            # Save the collection membership.
            $this->Membership->save(array(
                'Membership' => array(
                    'resource_id' => $this->Resource->id,
                    'collection_id' => $collection_id
            )));
            # Make a thumbnail (no need to make a Task for this, as we're
            # already outside the Request-Response loop).
            $this->makeThumb($this->Resource->id);
            # Reset the Resource and Membership models.
            $this->Resource->create();
            $this->Membership->create();
        }
        return 0;
    }
}

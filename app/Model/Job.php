<?php
App::uses('Sanitize', 'Utility');
/**
 * Job Model
 *
 * Defines some methods for more semantic (and convenient) use of our
 * little pretend job queue.
 *
 * @package    ARCS
 * @link       http://github.com/calmsu/arcs
 * @copyright  Copyright 2012, Michigan State University Board of Trustees
 * @license    BSD License (http://www.opensource.org/licenses/bsd-license.php)
 */
class Job extends AppModel {

    public $name      = 'Job';
    public $flatten   = true;
    public $recursive = -1;
    public $whitelist = array(
        'name', 'data', 'status', 'locked_at', 'locked_by', 'error',
        'failed_at', 'attempts'
    );

    /**
     * Job statuses
     */
    const        DONE = 0;
    const     PENDING = 1;
    const     FAILING = 2;
    const      FAILED = 3;
    const INTERRUPTED = 4;

    /**
     * Queues a job by creating a Job given a resource
     * id and a job name.
     *
     * @param string $name
     * @param string $data
     */
    public function enqueue($name, $data=array()) {
        return $this->add(array(
            'name' => $name,
            'data' => json_encode($data),
            'status' => self::PENDING,
            'progress' => 0
        ));
    }

    /**
     * Pops a job from the the queue. This amounts to finding
     * the oldest, uncompleted, non-active job in the table.
     *
     * @return mixed  array of job properties, or false if no 
     *                existing jobs meet these criteria.
     */
    public function pop() {
        $job = $this->find('first', array(
            'conditions' => array(
                'status' => array(self::PENDING, self::FAILING),
                'locked_at' => null
            ),
            'order' => array(
                'created' => 'DESC'
            )
        ));
        if (!$job) return false;
        $job['data'] = json_decode($job['data'], true);
        return $job;
    }

    /**
     * Lock a job so that other workers do not try to take it.
     *
     * @param string $id
     */
    public function lock($id, $locked_by) {

        $locked_by = Sanitize::escape($locked_by);
        $id = Sanitize::escape($id);

        # Try to lock it.
        $this->query(sprintf("
            UPDATE jobs 
            SET locked_at = '%s', locked_by = '%s'
            WHERE 
              id = '%s' AND 
              (locked_at IS NULL OR locked_by = '%s') AND
              failed_at IS NULL
            ", date('Y-m-d H:i:s'), $locked_by, $id, $locked_by));

        # Did we get it? (This seems necessary because the `query` method's 
        # return is inconsistent across DBs. Maybe there's a better way?)
        $maybe_locked = $this->findById($id);
        if ($maybe_locked['locked_by'] == $locked_by) return true;
        return false;
    }

    /**
     * Release any lock on a job so that other workers may take it.
     *
     * @param string $id
     */
    public function release($id) {
        $this->read(null, $id);
        $this->set(array(
            'locked_at' => null,
            'locked_by' => null
        ));
        return $this->save();
    }

    /**
     * Finish a job by deleting it from the queue.
     *
     * @param string $id
     */
    public function finish($id) {
        return $this->delete($id);
    }

    /**
     * Return the number of attempts for a particular job.
     *
     * @param string $id
     * @param string $error
     */
    public function numAttempts($id) {
        $job = $this->findById($id);
        return $job['attempts'];
    }

    /**
     * Set the job's status to FAILING.
     *
     * @param string $id
     */
    public function retryLater($id) {
        $job = $this->read(null, $id);
        $this->set(array(
            'status' => self::FAILING
        ));
        return $this->save();
    }

    /**
     * Finish the job, but with error. This means we'll set the error field
     * to the given error message, increment the attempts counter, and set
     * the status to FAILED.
     *
     * @param string $id
     * @param string $error
     */
    public function finishWithError($id, $error) {
        $job = $this->read(null, $id);
        $this->set(array(
            'status' => self::FAILED,
            'error' => $error,
            'locked_by' => null,
            'locked_at' => null,
            'attempts' => $job['attempts'] + 1,
            'failed_at' => date('Y-m-d H:i:s')
        ));
        return $this->save();
    }
}

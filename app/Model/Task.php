<?php

/**
 * Task Model
 *
 * Defines some methods for more semantic (and convenient) use of our
 * little pretend task queue.
 */
class Task extends AppModel {
    public $name = 'Task';
    public $flatten = true;
    public $recursive = -1;
    public $whitelist = array(
        'resource_id', 'job', 'data', 'status', 'active', 'progress', 'error'
    );

    /**
     * Queues a task by creating a Task given a resource
     * id and a job name.
     *
     * @param string $job
     * @param string $id
     * @param string $data
     */
    public function queue($job, $id, $data=null) {
        return $this->add(array(
            'resource_id' => $id,
            'job' => $job,
            'data' => $data,
            'status' => 1,
            'progress' => 0
        ));
    }

    /**
     * Pops a task from the the queue. This amounts to finding
     * the oldest, uncompleted, non-active task in the table.
     *
     * @return mixed  array of task properties, or false if no 
     *                existing tasks meet these criteria.
     */
    public function pop() {
        $task = $this->find('first', array(
            'conditions' => array(
                'Task.status' => 1,
                'Task.active' => false
            ),
            'order' => array(
                'Task.created' => 'ASC'
            )
        ));
        return $task ? $task : false;
    }

    /**
     * Marks a task active so that other workers do not try to take it.
     *
     * @param string $id
     */
    public function start($id) {
        $this->read(null, $id);
        $this->set('active', true);
        return $this->save();
    }

    /**
     * Marks a task non-active and sets the return status.
     *
     * @param string $id
     * @param int $status
     * @param string $error
     */
    public function done($id, $status=0, $error=null) {
        $this->read(null, $id);
        $this->set(array(
            'status' => $status,
            'active' => false,
            'error' => $error
        ));
        return $this->save();
    }
}

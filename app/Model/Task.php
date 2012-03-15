<?php

/**
 * Task Model
 *
 * Defines some methods for more semantic (and convenient) use of our
 * little pretend task queue.
 */
class Task extends AppModel {
    public $name = 'Task';

    /**
     * Queues a task by creating a Task given a resource
     * id and a job name.
     *
     * @param job
     * @param id
     * @param data
     */
    public function queue($job, $id, $data=null) {
        return $this->save(array(
            'Task' => array(
                'resource_id' => $id,
                'job' => $job,
                'data' => $data,
                'status' => 1,
                'progress' => 0
        )));
    }

    /**
     * Pops a task from the the queue. This amounts to finding
     * the oldest, uncompleted, non-active task in the table.
     *
     * @return    array containing task properties, or false if 
     *            no existing tasks meet these criteria.
     */
    public function pop() {
        $task = $this->find('first', array(
            'conditions' => array(
                'Task.status' => 1,
                'Task.in_progress' => false
            ),
            'order' => array(
                'Task.created' => 'ASC'
            )
        ));
        if (!$task) return false;
        return $task['Task'];
    }

    /**
     * Marks a task active so that other workers do not try to take it.
     *
     * @param id
     */
    public function start($id) {
        $this->read(null, $id);
        $this->set('in_progress', true);
        return $this->save();
    }

    /**
     * Marks a task non-active and sets the return status.
     *
     * @param id
     * @param status
     */
    public function done($id, $status=0) {
        $this->read(null, $id);
        $this->set('status', $status);
        $this->set('in_progress', false);
        return $this->save();
    }
}

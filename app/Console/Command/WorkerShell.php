<?php 

App::uses('Inflector', 'Core');
class WorkerShell extends AppShell {

    public $uses = array('Job');

    public $sleep     = 5;
    public $logLevel  = 0;

    const CRITICAL = 4;
    const    ERROR = 3;
    const     WARN = 2;
    const     INFO = 1;
    const    DEBUG = 0;

    /**
     * Checks the queue and delegates jobs.
     *
     * @return void
     */
    public function main() {
        $this->log('Starting worker.');
        while (true) {
            # Find a new job...
            $job = $this->Job->pop();
            # ...or if there aren't any:
            if (!$job) {
                $this->log("Failed to find a job.", self::DEBUG);
                if (!$this->params['server']) return;
                sleep($this->sleep);
                continue;
            }

            $id = $job['id'];
            $this->log("Starting work on $id ({$job['name']})", self::INFO);

            # Lock the job.
            if (!$this->Job->lock($id, $this->name)) 
                return $this->log("Could not acquire a lock on $id", self::ERROR);

            $this->log("Acquired lock on $id ({$job['name']})");

            try {
                # Dispatch the job.
                $name = Inflector::camelize($job['name']);
                $task = $this->Tasks->load($name);
                $task->execute($job['data']);
                $this->Job->finish($id);
                $this->log("Finished work on $id ({$job['name']})", self::INFO);
            } catch (Exception $e) {
                $this->Job->finishWithError($id, $e->getMessage());
                $this->log("Error on $id ({$job['name']})", self::ERROR);
            }
        }
    }

    /**
     * Prints out the startup message.
     *
     * @return void
     */
    public function startup() {
        $this->name = $this->getName();
        $this->out();
        $this->out("ARCS Worker ({$this->name})");
        $this->hr();
    }

    public function getName() {
        return trim(`hostname`) . ':' . getmypid();
    }

    public function log($msg, $severity=self::CRITICAL) {
        if ($severity >= $this->logLevel)
            printf("[%s] %s\n", date('r'), $msg);
    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();
        $parser->addOption('server', array(
            'short' => 's',
            'help' => 'Run the worker continuously, checking for new jobs ' .
                'on the configured interval.',
            'boolean' => true
        ));
        return $parser;
    }
}

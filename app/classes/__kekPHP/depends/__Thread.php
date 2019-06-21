<?php // @DEPRECATED

if (class_exists("Thread")) {
    class __Thread extends Worker
    {
        public $id;
        public $tid;

        public $args;
        public $cycles      = 0;
        public $data;
        public $db;
        public $exec_loaded = false;
        public $log;
        public $max_cycles  = -1;
        public $method;
        public $response;
        public $run_elapsed;
        public $run_started;
        public $run_stopped;
        public $task;

        public function __construct($method, $tid, $args = [])
        {
            $this->id = generate_thread_id();
            $this->tid = $tid;
            $this->args = $args;
            $this->data = [];
            $this->log = [];
            $this->method = $method;

            return $this->start();
        }

        function exec($method, $args = [])
        {
            $response = [];
            include(THREAD_PATH . "$method.php");

            return $response;
        }

        /** Check if this Thread is currently idle
         * @return bool
         */
        public function is_idle()
        {
            return $this->isJoined();
        }

        /** Check if this Thread is currently working
         * @return bool
         */
        public function is_started()
        {
            return $this->isStarted();
        }

        /** Check if this Thread is currently working
         * @return bool
         */
        public function is_working()
        {
            return !$this->isJoined();
        }

        public function log($message)
        {
            $this->log[] = "__Thread[$this->id]::log(): $message";
        }

        public function run()
        {
            //$this->run_started = microtime(true);
            //$this->log("run| Initialized | Method: $this->method");
            //$this->log("run| Thread Start @ $this->run_started");

            $this->data[] = $this->exec($this->method, $this->args);
            //$this->log("run| Cycle #$this->cycles | Task Response Received");

            //$this->run_stopped = microtime(true);
            //$this->run_elapsed = (float)($this->run_stopped-$this->run_started);
            //$this->log("run| Thread Complete @ $this->run_stopped");
            //$this->log("run| Time Elapsed | $this->run_elapsed Seconds");
        }
    }
}
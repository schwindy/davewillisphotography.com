<?php

if (class_exists('Pool', true)) {
    class __Pool extends Pool
    {
        public $data;
        public $work;

        function __construct($size = 5, $class = "Worker", array $args = [])
        {
            // $this->data = [];
            parent::__construct($size, $class, $args);
        }

        /**
         * (PECL pthreads &gt;= 2.0.0)
         * Returns the number of properties for this object
         * @link http://www.php.net/manual/en/threaded.count.php
         * @return int <p>Returns the number of properties for this object</p>
         */
        public function count()
        {

        }

        public function process()
        {
            while (count($this->work)) {
                $this->collect(function (__Task $task) {
                    // $obj = new stdClass();
                    // $obj->data = $task->data;
                    // $this->data[] = $obj;
                });
            }

            $this->shutdown();
        }
    }
} else {
    class __Pool
    {
    }
}

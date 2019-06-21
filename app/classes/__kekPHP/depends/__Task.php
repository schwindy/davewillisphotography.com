<?php
/*
 * kekPHP Threaded Task Wrapper
 * @Author: Robert Mariano Schwindaman (Git: schwindy | get.schwindy@gmail.com)
 * @Version: 0.2.1
 * @Updated: 03/23/2017
 * @Depends: PHP must be compiled with the pthreads extension and with ZTS enabled
 */
if (class_exists('Threaded', true)) {
    class __Task extends Threaded
    {
        public $id;
        public $args;
        public $method;

        public function __construct($method, &$args = false)
        {
            $this->args = $args;
            $this->method = $method;
        }

        public function run()
        {
            return call_user_func([$this, $this->method]);
        }
    }
} else {
    // echo "\nWARNING: DEFINING NON-THREADED __Task (this will break things!)";
    class __Task
    {
    }
}
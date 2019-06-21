<?php

class __Object
{
    function __construct($args = [])
    {
        if (empty($args)) {
            return false;
        }

        /*
         * Load $array into $this
         */
        foreach ($args as $key => $val) {
            if (empty($this->$key)) {
                $this->$key = '';
            }
            $this->$key = $val;
        }
    }
}
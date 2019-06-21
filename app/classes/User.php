<?php

class User extends BaseClass
{
    var $settings = 'json_array';
    var $storage_used_percent;

    function __construct($array = [])
    {
        parent::__construct($array);
    }

    function storage_used_percent()
    {
        if ((float)$this->storage_max === 0) {
            return 0;
        }

        return round((float)$this->storage_used / (float)$this->storage_max, 2) * 100;
    }
}
<?php

class Host extends BaseClass
{
    var $properties = 'json_array';

    function __construct($args = [])
    {
        if (is_string($args)) {
            $db = Database::getInstance();
            $args = $db->get_row("SELECT * FROM hosts WHERE id='$args'");
        }

        parent::__construct($args);
    }
}
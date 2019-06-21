<?php

class Doc extends BaseClass
{
    function __construct($array = [])
    {
        if (is_string($array)) {
            $db = Database::getInstance();
            $array = $db->get_row("SELECT * FROM docs WHERE id='$array'");
        }

        parent::__construct($array);
    }
}
<?php

class Shop extends BaseClass
{
    var $processing_fee = '__config';

    function __construct($array = [])
    {
        if (is_string($array)) {
            $db = Database::getInstance();
            $array = $db->get_row("SELECT * FROM parts WHERE part_num='$array'");
        }

        parent::__construct($array);
    }
}
<?php

class Payment extends BaseClass
{
    var $id;
    var $cost_btc = 'float';
    var $display_name;
    var $updated;

    function __construct($args = [])
    {
        if (is_string($args)) {
            $args = Database::getInstance()->get_row("SELECT * FROM payments WHERE id='$args'");
        }
        parent::__construct($args);
    }
}
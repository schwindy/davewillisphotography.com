<?php

class Plan extends BaseClass
{
    var $id;
    var $cost_btc = 'float';
    var $display_name;
    var $updated;

    function __construct($args = [])
    {
        if (is_string($args)) {
            $args = Database::getInstance()->get_row("SELECT * FROM plans WHERE id='$args'");
        }
        parent::__construct($args);
    }
}
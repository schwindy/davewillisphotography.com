<?php

class Sale extends BaseClass
{
    var $cart = 'json_array';
    var $cart_total_human;
    var $customer_name;

    function __construct($array = [])
    {
        parent::__construct($array);
    }

    function cart_html()
    {
        return "$" . $this->cart_total;
    }

    function cart_total_human()
    {
        return "$" . $this->cart_total;
    }

    function customer_name()
    {
        $db = Database::getInstance();
        $row = $db->get_row("SELECT * FROM customers WHERE id='" . $this->customer_id . "'");

        return $row['name'];
    }
}
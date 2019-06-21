<?php

class Asset extends BaseClass
{
    var $display_url;

    function __construct($array = [])
    {
        parent::__construct($array);
    }

    function display_url()
    {
        return '/admin/assets/view?id=' . $this->id;
    }
}
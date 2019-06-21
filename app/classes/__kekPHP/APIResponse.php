<?php

class APIResponse extends Response
{
    var $status;
    var $message;
    var $data;
    var $timestamp;

    function __construct($status = 0, $message = null, $data = null)
    {
        parent::__construct($status, $message, $data);
        echoJSON($this);
    }
}
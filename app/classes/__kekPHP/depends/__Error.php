<?php

class __Error
{
    var $file;
    var $line;
    var $message;
    var $type;

    function __construct($args = [])
    {
        $this->message = $args['message'];
        $this->file = $args['file'];
        $this->line = $args['line'];

        switch ($this->error_id) {
            case E_USER_ERROR:
                $this->type = 'error';
                break;

            case E_USER_WARNING:
                $this->type = 'warn';
                break;

            case E_USER_NOTICE:
                $this->type = 'notice';
                break;

            default:
                $this->type = 'notice';
        }
    }
}
<?php

class File extends BaseClass
{
    var $host;
    var $properties = 'json_array';
    var $url;

    function __construct($args = [])
    {
        if (is_string($args)) {
            $db = Database::getInstance();
            $args = $db->get_row("SELECT * FROM files WHERE id='$args'");
        }

        parent::__construct($args);
    }

    function host()
    {
        return __get('hosts', ['where' => "id='$this->host'"]);
    }

    function url()
    {
        return $this->host->protocol . '://' . $this->host->domain . $this->host->path;
    }
}
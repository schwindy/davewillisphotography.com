<?php

class Ticket extends BaseClass
{
    function __construct($array = [])
    {
        parent::__construct($array);
    }

    /** Force $this->viewer_count to Integer
     * @return int
     */
    //function viewer_count()
    //{
    //    return (int)$this->viewer_count;
    //}
}
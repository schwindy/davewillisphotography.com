<?php

/**
 * @Class Response
 *
 * @Description This class represents a kekPHP Response Object
 *
 * @Author: Robert Mariano Schwindaman (Git: schwindy | get.schwindy@gmail.com)
 * @Version: 0.1
 * @Note: This class is subject to change.
 *
 * @StatusCodes:
 * -1: Fatal   - A Fatal Exception was caught (Exception Object stored in $this->data)
 *  0: Error   - An Application Error has occurred (data may be available in $this->data, depends on the dev)
 *  1: Success - Code Execution Completed Successfully
 *  2: Warning - Code Execution Completed Successfully BUT something should "probably" be looked at and fixed
 *  3: Notice  - Code Execution Completed Successfully BUT this may be useful for debugging
 *  4: Info    - Code Execution Completed Successfully BUT this may give more details about how it went
 *
 * These status codes allow for "halt execution" logic as so:
 *
 * if(!$response->status)return $response;
 *
 * @Example: JSON
 * {
 *     "status":1,
 *     "message": "Method run successfully!",
 *     "data": {
 *         "id":"ASDFGH-123456"
 *     },
 *     "timestamp": 123456789,
 * }
 */
class Response
{
    public static $statuses = [
        'fatal'   => -1,
        'error'   => 0,
        'success' => 1,
        'warning' => 2,
        'notice'  => 3,
        'info'    => 4,
    ];

    function __construct($status = 0, $message = "", $data = [])
    {
        if (is_integer($status)) {
            $this->status = $status;
            $this->message = $message;
            $this->data = $data;
        } else {
            if (is_object($status)) {
                $this->status = $status->status;
                $this->message = $status->message;
                $this->data = $status->data;
            } else {
                $this->status = $status['status'];
                $this->message = $status['message'];
                $this->data = $status['data'];
            }
        }

        $this->timestamp = microtime(true);
    }
}
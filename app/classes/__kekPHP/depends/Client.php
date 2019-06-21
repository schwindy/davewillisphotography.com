<?php

/**
 * @Class Client
 *
 * @Description kekPHP Client Base Class
 *
 * @Author: Robert Mariano Schwindaman (Git: schwindy | get.schwindy@gmail.com)
 * @Version: 0.1
 * @Note: This class is subject to change.
 */
class Client
{
    public static $archive_table = "minutes_archive_12_04_2017";
    public static $log_table     = "logs";
    public static $save_logs     = false;

    function __construct($args = [])
    {

    }

    public static function __echo($caller, $status = 0, $message = '', $data = false)
    {
        $response = new Response($status, $message, $data);
        echo "\n$caller(): " . vpre($response);

        return $response;
    }

    /**
     * Calculate the absolute and % difference between two values
     *
     * @param float $archiveVal
     * @param float $nowVal
     * @return array|Response
     */
    public static function calculateDelta($archiveVal, $nowVal)
    {
        $archiveVal = (float)$archiveVal;
        $nowVal = (float)$nowVal;

        /* Enforce Argument Validation */
        $validation = ClientValidator::calculateDelta($archiveVal, $nowVal);
        if (!$validation->status) {
            return $validation;
        }

        $delta_abs = ($nowVal - $archiveVal);
        $delta = ($delta_abs / $archiveVal) * 100;
        if (empty($delta) || is_nan($delta) || is_string($delta)) {
            $delta = -1;
        }

        return new Response(1, "Successfully Calculated Delta!", [
            'delta'     => $delta,
            'delta_abs' => $delta_abs,
        ]);
    }

    /**
     * Convert a Model Name to a Table Name
     *
     * @param string $model
     * @return string|bool
     */
    public static function convertModelToTable($model)
    {
        if (empty($model)) {
            return false;
        }

        return str_plural($model);
    }

    /**
     * Log an error
     * @alias Client::log
     *
     * @param $title
     * @param $message
     * @param @optional $data
     * @return Response
     */
    public static function error($title, $message, $data = null)
    {
        return Client::log($title, $message, $data, 'error');
    }

    /**
     * Retrieve a Database Instance
     *
     * @return Database
     */
    public static function getDatabase()
    {
        return Database::getInstance();
    }

    /** Create an ArchiveClient Log
     *
     * @param string $title
     * @param string $message
     * @param @optional string $data
     * @param string $type
     * @return Response
     */
    public static function log($title, $message = "", $data = null, $type = 'notice')
    {
        if (Client::$save_logs) {
            Client::getDatabase()->insert(Client::$log_table, [
                'data'        => $data,
                'id'          => generate_mysql_id(),
                'parent_id'   => __METHOD__,
                'parent_type' => __CLASS__,
                'message'     => $message,
                'title'       => $title,
                'type'        => $type,
            ]);
        }

        return Client::__echo(__METHOD__, Response::$statuses[$type] ?? -2, "$title: $message", $data);
    }

    /**
     * Log a warning
     * @alias Client::log
     *
     * @param $title
     * @param $message
     * @param @optional $data
     * @return Response
     */
    public static function warn($title, $message, $data = null)
    {
        return Client::log($title, $message, $data, 'warning');
    }

    /**
     * Parse raw timestamp input and return a cleaned timestamp always expressed as an integer
     *
     * @param $timestamp
     * @return int|false
     */
    public static function parseTime($timestamp)
    {
        if (empty($timestamp)) {
            return false;
        }

        $timestamp = (int)$timestamp;

        /* Negative Time Values are considered an offset from now expressed in seconds */
        if ($timestamp < 0) {
            return time() + $timestamp;
        }

        return $timestamp;
    }
}
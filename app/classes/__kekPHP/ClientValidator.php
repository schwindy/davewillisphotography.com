<?php

/**
 * @Class ClientValidator
 *
 * @Description Validate Client Method Arguments
 *
 * @Author: Robert Mariano Schwindaman (Git: schwindy | get.schwindy@gmail.com)
 * @Version: 0.1
 * @Created 11/04/2017
 */
class ClientValidator extends Validator
{
    public static $SUCCESS_MESSAGE = "Successfully Validated Arguments";

    public static function calculateDelta($archiveVal, $nowVal)
    {
        $args = [
            'archiveVal' => $archiveVal,
            'nowVal'     => $nowVal,
        ];

        foreach ($args as $field => $arg) {
            if (empty($arg)) {
                return new Response(0, "Invalid Arguments | Reason: $field is empty", false);
            }

            if (is_nan($arg)) {
                return new Response(0, "Invalid Arguments | Reason: $field is NaN", false);
            }
        }

        return new Response(1, ClientValidator::$SUCCESS_MESSAGE, true);
    }

    /**
     * Determines if the supplied arguments are valid
     *
     * @param array $args
     * @return Response
     */
    public function renderDelta($args)
    {
        if (!is_array($args)) {
            return new Response(0, "Invalid Arguments | Reason: args is non-array", $args);
        }

        $not_empty = [
            'field',
            'model',
            'obj_id',
            'start',
        ];

        foreach ($not_empty as $field) {
            if (empty($args[$field])) {
                return new Response(0, "Invalid Arguments | Reason: args[$field] is empty", $args);
            }
        }

        return new Response(1, "Successfully Validated Arguments", true);
    }

    /**
     * Determines if the supplied arguments are valid
     *
     * @param array $args
     * @return Response
     */
    public function renderTimeSeries($args)
    {
        if (!is_array($args)) {
            return new Response(0, "Invalid Arguments | Reason: args is non-array", $args);
        }

        $not_empty = [
            'field',
            'model',
            'obj_id',
            'start',
            'stop',
        ];

        foreach ($not_empty as $field) {
            if (empty($args[$field])) {
                return new Response(0, "Invalid Arguments | Reason: args[$field] is empty", $args);
            }
        }

        return new Response(1, "Successfully Validated Arguments", true);
    }
}
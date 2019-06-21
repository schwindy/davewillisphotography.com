<?php
/** kekPHP Error Log that outputs PHP Logs to the Browser Console prior to Standard PHP Error Logging */
function __error()
{
    set_error_handler('__error_callback', PHP_LOG_LEVEL);
    error_reporting(E_ERROR);
}

/** Callback executed on PHP Errors to output PHP Errors to Javascript console.log
 * @param $error_id
 * @param $message
 * @param $file
 * @param $line
 * @return false
 */
function __error_callback($error_id, $message, $file, $line)
{
    $error = new __Error
    ([
        'file'     => str_replace(BASE_PATH, "", $file),
        'line'     => $line,
        'message'  => $message,
        'error_id' => $error_id,
    ]);

    if ($error->type === 'error') {
        $error = json_encode($error);
        if (strpos($error, '\/') !== false) {
            $error = str_replace('\\', "", $error);
        }
        $needle = "Couldn't fetch mysqli";
        if (strpos($error, $needle) !== false) {
            $error = str_replace($needle, "Could not fetch mysqli", $error);
        }

        if (KEK_LOG_LEVEL !== 'secure') {
            echo "<script>console.error({'message':'PHP (error): ', 'data':'$error'})</script>";
        }
        exit(1);
    }

    $needle = "Couldn't fetch mysqli";
    if (strpos($error->message, $needle) !== false) {
        $error->message = str_replace($needle, "Could not fetch mysqli", $error->message);
    }
    if (KEK_LOG_LEVEL !== 'secure') {
        echo __log("PHP (" . $error->type . "): ", $error);
    }

    return true;
}
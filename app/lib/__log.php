<?php
/** Use console.log() (Javascript)
 * @param string $message
 * @param string $data
 * @return string $html - kekPHP Log <script> Element
 */
function __log($message = '', $data = '')
{
    if (empty($data)) {
        return "\n<script>__logs.push({'message':'$message'})</script>";
    }

    if (is_array($data)) {
        $data = json_encode($data);
    } else {
        if (!is_string($data)) {
            $data = json_encode(($data));
        }
    }

    $no_quotes = false;
    if (strpos($data, '\/') !== false) {
        $data = str_replace('\\', "", $data);
    }
    if (strpos($data, '["') !== false) {
        $no_quotes = true;
        $data = str_replace('["', "{\"data\":\"", str_replace('"]', "\"}", json_encode($data)));
    }

    if (strpos($data, '\/') !== false) {
        $data = str_replace('\\', "", $data);
    }

    if (!$no_quotes) {
        $data = "$data";
    }

    return "\n<script>__logs.push({'message':'$message', 'data':'$data'})</script>";
}
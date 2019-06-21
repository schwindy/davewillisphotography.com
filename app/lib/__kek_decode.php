<?php
function __kek_decode($data, $arr, $dry = false)
{
    $arr = array_merge($_REQUEST, (array)$arr);
    $token_start_needle = "__@";
    $token_end_needle = "@__";
    $count = 0;
    while (strpos($data, $token_start_needle) !== false) {
        $token_start = strpos($data, $token_start_needle);
        $token_end = strpos($data, $token_end_needle) + strlen($token_end_needle);
        $token_full = substr($data, $token_start, $token_end - $token_start);
        $token = str_replace($token_start_needle, "", $token_full);
        $token = str_replace($token_end_needle, "", $token);

        if (!empty($arr[$token]) || !$dry) {
            $data = str_replace($token_full, $arr[$token], $data);
        } else {
            $data = str_replace($token_full, "@$token", $data);
        }
        $count++;
        if ($count > 25) {
            break;
        }
    }

    $data = preg_replace('/((\w*)([|][>]))/i', '<${2}>', $data);
    $data = preg_replace('/([<][|])(\w*)/i', '</${2}>', $data);
    $data = str_replace("\n", '', $data);

    // Custom Element Behaviors
    $data = str_replace('</card>', '</div>', str_replace('<card>', '<div class="card">', $data));
    $data = str_replace('<i>', '<i class="material-icons white">', $data);
    $data = str_replace('</search>', '</div>', str_replace('<search>', __html('search'), $data));

    return $data;
}
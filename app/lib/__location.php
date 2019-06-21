<?php
/** Sends a Location header
 * @param string $path The path to redirect to
 * @param array $args Optional URL Parameters (GET)
 */
function redirect_to($path = '/', $args = [])
{
    if ($path !== '/' && !empty($args)) {
        $path .= "?";
        foreach ($args as $key => $val) {
            $path .= "$key=$val&";
        }
        $path = str_lreplace("&", "", $path);
    }

    header("Location: $path");
    echo "<script>location.href = '$path';</script>";
}
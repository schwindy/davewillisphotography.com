<?php

function curl_delete($url, $data = '', $headers = [])
{
    $options = [
        CURLOPT_CUSTOMREQUEST  => 'PUT',
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_PORT           => 80,
        CURLOPT_POSTFIELDS     => $data,
        CURLOPT_RETURNTRANSFER => true,
        // CURLOPT_VERBOSE => true,
    ];

    $handle = curl_init($url);
    curl_setopt_array($handle, $options);
    $result = curl_exec($handle);

    return $result;
}

function curl_enable_debug($curl)
{
    curl_setopt($curl, CURLOPT_VERBOSE, true);
}

function curl_get($url, $data = [], $bg = false)
{
    $bg = $bg ? "&" : "";
    if (!empty($data)) {
        $data = request_to_str($data);
    }

    $cmd = "curl -s --data '$data' $url $bg";

    return exec($cmd);
}

function curl_get2($url, $headers = [])
{
    $options = [
        CURLOPT_ENCODING      => "",
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTP_VERSION  => CURL_HTTP_VERSION_1_1,
        CURLOPT_HTTPHEADER    => $headers,
        CURLOPT_MAXREDIRS     => 10,
        CURLOPT_PORT          => 80,
        CURLOPT_RETURNTRANSFER,
        true,
        CURLOPT_TIMEOUT       => 30,
        CURLOPT_URL           => $url,
        CURLOPT_VERBOSE       => false
    ];

    $handle = curl_init($url);
    curl_setopt_array($handle, $options);

    ob_start();
    curl_exec($handle);
    $result = ob_get_clean();
    // echo "\ncurl_get2(): Error: ";echo vpre(curl_error($handle));
    curl_close($handle);

    return $result;
}

function curl_post($url, $data = '', $headers = [])
{
    $options = [
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_POST           => 1,
        CURLOPT_POSTFIELDS     => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL            => $url,
        // CURLOPT_VERBOSE => true,
    ];

    $handle = curl_init($url);
    curl_setopt_array($handle, $options);
    $result = curl_exec($handle);
    // echo "\ncurl_post(): Error: ";echo vpre(curl_error($handle));
    curl_close($handle);

    return $result;
}

function curl_put($url, $data = '', $headers = [])
{
    $options = [
        CURLOPT_CUSTOMREQUEST  => 'PUT',
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_PORT           => 80,
        CURLOPT_POSTFIELDS     => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL            => $url,
        // CURLOPT_VERBOSE => true,
    ];

    $handle = curl_init($url);
    curl_setopt_array($handle, $options);
    $result = curl_exec($handle);
    // echo "\ncurl_put(): Error: ";echo vpre(curl_error($handle));
    curl_close($handle);

    return $result;
}

function request_to_str($data = [])
{
    $str = "";
    foreach ($data as $key => $val) {
        if (is_array($val) || is_object($val)) {
            $val = json_encode($val);
        }

        $str .= "$key=" . $val . "&";
    }

    return str_lreplace("&", "", $str);
}

function request_to_url($path, $data = [])
{
    if (empty($data)) {
        $data = $_REQUEST;
    }

    if (empty($data) || $path === '/') {
        return $path;
    }

    if (strpos($path, "?") === false) {
        $path .= "?";
    } else {
        $path .= "&";
    }

    foreach ($data as $key => $val) {
        if (is_array($val) || is_object($val)) {
            $val = json_encode($val);
        }

        $path .= "$key=" . urlencode($val) . "&";
    }

    return str_lreplace("&", "", $path);
}
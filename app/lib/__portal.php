<?php

function __portal($run, $args = [])
{
    $args['run'] = $run;

    return json_decode(curl_get(request_to_url(PORTAL_URL, $args)));
}
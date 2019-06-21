<?php

function kekinfo()
{
    $info = [
        "ENVIRONMENT" => ENVIRONMENT,
    ];

    new APIResponse(1, "kekPHP is running (monkey)", $info);
}
<?php

function hashpls()
{
    if (empty($_REQUEST['string'])) {
        new APIResponse(0, "Missing parameter: string", sha1(PJSalt . $_REQUEST['string']));
    }

    new APIResponse(1, "Consider it hashed!", sha1(PJSalt . $_REQUEST['string']));
}
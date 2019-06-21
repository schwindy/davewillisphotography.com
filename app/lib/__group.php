<?php

function __group()
{
    $_SESSION['group'] = empty($_SESSION['group']) ? __config('DEFAULT_GROUP') : $_SESSION['group'];
}
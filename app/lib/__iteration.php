<?php

function found($haystack, $property, $needle)
{
    $found = false;
    foreach ($haystack as $value) {
        if ($value->$property === $needle) {
            $found = true;
            break;
        }
    }

    return $found;
}
<?php

function array_contains($arr, $search, $property = '')
{
    if (!empty($property)) {
        foreach ($arr as $val) {
            if ($val->$property === $search) {
                return true;
            } else {
                foreach ($arr as $val) {
                    if ($val === $search) {
                        return true;
                    }
                }
            }
        }
    }

    return false;
}
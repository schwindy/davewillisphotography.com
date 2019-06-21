<?php

function __dropdown_order_method()
{
    $body = "";
    $id = "method";
    $types = ["limit", "market"];

    foreach ($types as $x) {
        $body .= "<option name='$id' value='$x'>" . ucfirst($x) . "</option>";
    }

    return "<select class='width_75 padding_sm margin_sm_y font_xlg' id='$id' name='$id' class='var val'>$body</select>";
}
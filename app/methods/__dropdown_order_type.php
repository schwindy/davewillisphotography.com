<?php

function __dropdown_order_type()
{
    $body = "";
    $id = "type";
    $types = ["buy", "sell"];

    foreach ($types as $x) {
        $body .= "<option name='$id' value='$x'>" . ucfirst($x) . "</option>";
    }

    return "<select class='width_75 padding_sm margin_sm_y font_xlg' id='$id' name='$id' class='var val'>$body</select>";
}
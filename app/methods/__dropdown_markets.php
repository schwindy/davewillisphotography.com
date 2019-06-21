<?php

function __dropdown_markets()
{
    $body = "";
    $db = Database::getInstance();
    $id = 'market_id';
    $rows = $db->get_rows("SELECT * FROM markets ORDER BY display_name ASC");
    foreach ($rows as $x) {
        $body .= "<option name='$id' value='$x[id]'>$x[display_name]</option>";
    }

    return "<select class='width_75 padding_sm margin_sm_y font_xlg' id='$id' name='$id' class='var val'>$body</select>";
}
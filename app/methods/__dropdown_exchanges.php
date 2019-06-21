<?php

function __dropdown_exchanges()
{
    $body = "";
    $db = Database::getInstance();
    $id = 'exchange_id';
    $rows = $db->get_rows("SELECT * FROM exchanges ORDER BY display_name ASC");
    foreach ($rows as $x) {
        if (!class_exists("XAPI_" . ucfirst($x['id']))) {
            continue;
        }
        $body .= "<option name='$id' value='$x[id]'>" . ucfirst($x['display_name']) . "</option>";
    }

    return "<select class='width_75 padding_sm margin_sm_y font_xlg' id='$id' name='$id' class='var val'>$body</select>";
}
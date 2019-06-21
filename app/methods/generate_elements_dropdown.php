<?php

function generate_elements_dropdown($id = 'id')
{
    $db = Database::getInstance();
    $body = "<option name='$id' value=''>- Choose an Element -</option>";
    $elements = $db->get_rows("SELECT * FROM elements ORDER BY type ASC, display_name ASC");

    foreach ($elements as $element) {
        if (is_object($element)) {
            $element = (array)$element;
        }
        $element['display_name'] = "$element[type] | $element[display_name]";
        $body .= "<option name='$id' value='$element[id]'>$element[display_name]</option>";
    }

    return "<select name='$id' class='$id width_100 padding_xsm_x padding_xxsm_y margin_sm_y'>$body</select>";
}
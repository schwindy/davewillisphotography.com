<?php

function generate_templates_dropdown($id = 'template_id')
{
    $db = Database::getInstance();
    $body = "<option name='$id' value=''>- Choose a Reply -</option>";
    $templates = $db->get_rows("SELECT * FROM templates ORDER BY display_name ASC");

    foreach ($templates as $template) {
        if (is_object($template)) {
            $template = (array)$template;
        }
        $body .= "<option name='$id' value='$template[id]'>$template[display_name]</option>";
    }

    return "<select id='$id' class='width_100 padding_xsm_x padding_xxsm_y margin_sm_y'>$body</select>";
}
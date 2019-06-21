<?php

function generate_configuration_element()
{
    $db = Database::getInstance();

    if (empty($page)) {
        $page = new Page($db->get_row("SELECT * FROM pages WHERE id='$_REQUEST[id]'"));
        if (empty($page)) {
            return false;
        }
    }

    $html = "<div class='elements card'>";
    $count = 0;
    foreach ($page->body['elements'] as $id) {
        $element = $db->get_row("SELECT * FROM elements WHERE id='$id'");
        $html .= "<div id='$element[id]' class='element'>
                <p class='z_index'>$count</p>
                <p class='display_name'>$element[display_name]</p>
                <button class='move_up'>Move Up</button>
                <button class='move_down'>Move Down</button>
                <button class='remove'>Remove</button>
            </div>";

        $count++;
    }

    $html .= "<button id='submit' class='width_100 padding_sm_y border_0 blue_bg' title='Submit'>
            <img src='/img/icons/input_icon.png'>
            <h2 class='white bold'>Save</h2>
        </button>";

    $html .= "</div>";

    return $html;
}
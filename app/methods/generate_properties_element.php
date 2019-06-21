<?php

function generate_properties_element($element = [], $skips = [])
{
    $html = "<div class='properties'>";
    if (is_array($skips)) {
        $skips[] = 'options';
    }

    if (!empty($element)) {
        foreach ($element->properties as $var => $val) {
            $skip = false;
            foreach ($skips as $s) {
                if ($var === $s) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) {
                continue;
            }

            $html .= "<div class='property'>
                    <input class='var' placeholder='Property Name' value='$var'>
                    <input class='val' placeholder='Property Value' value='$val'>
                    <button class='remove padding_xxsm_y red_bg white bold'>Remove</button>
                </div>";
        }
    }

    $html .= "<button id='add_property' class='width_100 padding_sm_y border_0 purple_bg' title='Add Property'>
            <h3 class='white bold'>Add New Property</h3>
        </button><br>";

    $html .= "<button id='kek_submit' class='width_100 padding_sm_y border_0 blue_bg' title='Submit'>
            <img src='/img/icons/input_icon.png'>
            <h2 class='white bold'>Save</h2>
        </button>";

    $html .= "</div>";

    if ($skips !== 'nojs') {
        $html .= __html('script', ['src' => '/js/kek/properties.js']);
    }

    $html .= __html('css', ['href' => '/css/classes/Properties.css']);

    return $html;
}
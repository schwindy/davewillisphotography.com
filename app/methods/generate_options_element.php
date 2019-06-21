<?php

function generate_options_element($element = [], $skips = [])
{
    $html = "<div class='options'>";

    if (is_array($skips)) {
        $skips[] = 'options';
    }

    if (!empty($element)) {
        foreach ($element->options as $var => $val) {
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

            $html .= "<div class='option'>
                    <input class='var' placeholder='Option Type' value='$var'>
                    <input class='val' placeholder='Option Name' value='$val'>
                    <button class='remove padding_xxsm_y red_bg white bold'>Remove</button>
                </div>";
        }
    }

    $html .= "<button id='add_option' class='width_100 padding_sm_y border_0 purple_bg' title='Add Option'>
            <h3 class='white bold'>Add New Option</h3>
        </button><br>";

    $html .= "</div>";

    if ($skips !== 'nojs') {
        $html .= __html('js', ['src' => '/js/kek/options.js']);
    }

    $html .= __html('css', ['href' => '/css/classes/Options.css']);

    return $html;
}
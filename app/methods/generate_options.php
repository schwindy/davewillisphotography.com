<?php

function generate_options($options)
{
    $html = "<div class='options'>";

    foreach ($options as $name => $type) {
        if ($name === 'color') {
            if ($type === 'canvas_color') {
                $data = [
                    'beige'        => 'Beige',
                    'black'        => 'Black',
                    'navy_blue'    => 'Blue - Navy',
                    'pacific_blue' => 'Blue - Pacific',
                    'green'        => 'Green',
                    'burgundy'     => 'Red - Burgundy',
                ];
            }

            $body = "<option name='$name' value=''>--- Choose a Color ---</option>";
        } elseif ($name === 'size') {
            if ($type === 'clothing_size') {
                $data = [
                    'x_small'  => 'XSM',
                    'small'    => 'SM',
                    'medium'   => 'MD',
                    'large'    => 'LG',
                    'x_large'  => 'XL',
                    '2x_large' => 'XXL',
                    '3x_large' => 'XXXL',
                    '4x_large' => 'XXXXL',
                ];
            }

            $body = "<option name='$name' value=''>--- Choose a Size ---</option>";
        }

        foreach ($data as $key => $val) {
            $body .= "<option name='$key' value='$key'>$val</option>";
        }

        $html .= "<select name='$name' class='option width_90 text_center padding_xxsm_y margin_xsm_bottom'>$body</select>";
    }

    $html .= "</div>";

    return $html;
}
<?php
function get_volume_rounded($volume)
{
    if ($volume < 1) {
        return round($volume, 8);
    }
    while ($volume > 1000) {
        $volume = $volume / 1000;

        if (empty($unit)) {
            $unit = "K";
        } else {
            if ($unit === "K") {
                $unit = "M";
            } else {
                if ($unit === "M") {
                    $unit = "B";
                } else {
                    if ($unit === "G") {
                        $unit = "T";
                    }
                }
            }
        }
    }

    if (empty($unit)) {
        $unit = "";
    }

    return round($volume, 2) . $unit;
}

function round_human($num)
{
    if ($num < 1) {
        return round($num, 6);
    }

    return round($num, 2);
}
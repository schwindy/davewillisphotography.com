<?php

function add_commas_num($str)
{
    if (empty($str)) {
        return 0;
    }
    $result = "";
    $count = 0;

    for ($i = strlen($str) - 1; $i > -1; $i--) {
        $result = $result . $str[$i];

        if ($count === 2 && $i - 1 > -1) {
            $result = $result . ',';
            $count = 0;
            continue;
        }

        $count++;
    }

    return strrev($result);
}

function br2nl($str)
{
    return str_replace("<br>", "\n", $str);
}

function str_clean($str)
{
    if (empty($str)) {
        return false;
    }
    $str = str_split($str);

    $result = "";
    foreach ($str as $c) {
        if ($c >= "a" && $c <= "z") {
            $result .= $c;
        } else {
            if ($c >= "A" && $c <= "Z") {
                $result .= $c;
            } else {
                if ($c >= "0" && $c <= "9") {
                    $result .= $c;
                }
            }
        }
    }

    return $result;
}

function str_contains($haystack, $needle)
{
    if (strpos($haystack, $needle) !== false) {
        return true;
    }

    return false;
}

function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);
    if ($pos !== false) {
        return substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}

function str_plural($str)
{
    $plural = [
        '(matr|vert|ind)(ix|ex)'                                             => '\1ices',
        '(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us' => '\1i',
        '(buffal|tomat)o'                                                    => '\1oes',
        'x'                                                                  => 'xes',
        'ch'                                                                 => 'ches',
        'sh'                                                                 => 'shes',
        'ss'                                                                 => 'sses',
        'ay'                                                                 => 'ays',
        'ey'                                                                 => 'eys',
        'iy'                                                                 => 'iys',
        'oy'                                                                 => 'oys',
        'uy'                                                                 => 'uys',
        'y'                                                                  => 'ies',
        'ao'                                                                 => 'aos',
        'eo'                                                                 => 'eos',
        'io'                                                                 => 'ios',
        'oo'                                                                 => 'oos',
        'uo'                                                                 => 'uos',
        'o'                                                                  => 'os',
        'us'                                                                 => 'uses',
        'cis'                                                                => 'ces',
        'sis'                                                                => 'ses',
        'xis'                                                                => 'xes',
        'zoon'                                                               => 'zoa',
        'itis'                                                               => 'itis',
        'ois'                                                                => 'ois',
        'pox'                                                                => 'pox',
        'ox'                                                                 => 'oxes',
        'foot'                                                               => 'feet',
        'goose'                                                              => 'geese',
        'tooth'                                                              => 'teeth',
        'quiz'                                                               => 'quizzes',
        'alias'                                                              => 'aliases',
        'alf'                                                                => 'alves',
        'elf'                                                                => 'elves',
        'olf'                                                                => 'olves',
        'arf'                                                                => 'arves',
        'nife'                                                               => 'nives',
        'life'                                                               => 'lives'
    ];

    $irregular = [
        'leaf'   => 'leaves',
        'loaf'   => 'loaves',
        'move'   => 'moves',
        'foot'   => 'feet',
        'goose'  => 'geese',
        'genus'  => 'genera',
        'sex'    => 'sexes',
        'ox'     => 'oxen',
        'child'  => 'children',
        'man'    => 'men',
        'tooth'  => 'teeth',
        'person' => 'people',
        'wife'   => 'wives',
        'mythos' => 'mythoi',
        'testis' => 'testes',
        'numen'  => 'numina',
        'quiz'   => 'quizzes',
        'alias'  => 'aliases',
    ];

    $uncountable = [
        'sheep',
        'fish',
        'deer',
        'series',
        'species',
        'money',
        'rice',
        'information',
        'equipment',
        'news',
        'people',
    ];

    if (in_array(strtolower($str), $uncountable)) {
        return $str;
    }

    foreach ($irregular as $pattern => $result) {
        $searchPattern = '/' . $pattern . '$/i';
        if (preg_match($searchPattern, $str)) {
            $replacement = preg_replace($searchPattern, $result, $str);
            if (preg_match('/^[A-Z]/', $str)) {
                $replacement = ucfirst($replacement);
            }

            return $replacement;
        }
    }

    foreach ($plural as $pattern => $result) {
        $searchPattern = '/' . $pattern . '$/i';
        if (preg_match($searchPattern, $str)) {
            return preg_replace($searchPattern, $result, $str);
        }
    }

    return $str . 's';
}

function str_singular($str)
{
    return str_lreplace("s", "", $str);
}

function str_splice($str, $needle_start, $needle_end = null)
{
    if (empty($needle_end)) {
        $needle_end = substr($needle_start, 0, 1) . '/' . substr($needle_start, 1);
    }
    $start = strpos($str, $needle_start) + strlen($needle_start);
    $length = strpos($str, $needle_end) - $start;
    $result = substr($str, $start, $length);

    return $result;
}

function str_to_class($str)
{
    return str_replace(" ", "", ucwords(str_replace("_", " ", $str)));
}
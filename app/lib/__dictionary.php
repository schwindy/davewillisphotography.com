<?php

function __dictionary($index, $data = [])
{
    $db = Database::getInstance();
    if (empty($data)) {
        return json_decode($db->get_row("SELECT data FROM __dictionary WHERE id='$index'")['data']);
    }

    $data = json_encode($data);
    $match = $db->get_row("SELECT * FROM __dictionary WHERE id='$index'");
    if (empty($match)) {
        $db->insert('__dictionary', ['id' => $index, 'data' => $data,]);
    } else {
        $db->update('__dictionary', ['data' => "$data",], ['id' => $index]);
    }

    return true;
}
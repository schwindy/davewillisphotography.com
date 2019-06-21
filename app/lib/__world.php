<?php

function __world($run, $args = [])
{
    $args['run'] = $run;

    return json_decode(curl_get(request_to_url(WORLD_URL, $args)));
}

function __world_get($type)
{
    $db = Database::getInstance();
    $rows = $db->get_rows("SELECT * FROM __world WHERE type='$type' ORDER BY page ASC");
    if (empty($rows)) {
        return false;
    }

    $world = '';
    foreach ($rows as $row) {
        //echo vpre(json_decode($row['data']));exit;
        $world .= json_decode($row['data']);
    }

    return json_decode($world);
}

function __world_set($type, $page, $data = "")
{
    $db = Database::getInstance();
    $id = $type . "[__@$page@__]";
    $data = json_encode($data);
    $match = $db->get_row("SELECT * FROM __world WHERE id='$id'");
    if (empty($match)) {
        return $db->insert('__world', [
            'id'   => $id,
            'type' => $type,
            'page' => (int)$page,
            'data' => $data,
        ]);
    } else {
        return $db->update('__world', ['data' => $data, 'page' => (int)$page], ['id' => $id]);
    }
}
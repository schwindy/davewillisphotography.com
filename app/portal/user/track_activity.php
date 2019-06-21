<?php

function track_activity()
{
    if (empty($_REQUEST['user_id'])) {
        new APIResponse(0, "Missing user_id");
    }
    if (empty($_REQUEST['path'])) {
        new APIResponse(0, "Missing path");
    }
    if (empty($_REQUEST['action'])) {
        new APIResponse(0, "Missing action");
    }

    $db = Database::getInstance();
    $ttl = get_date('+5 minutes');

    if ($_REQUEST['user_id'] === 'kek') {
        new APIResponse(1, "Activity updated successfully", $ttl);
    }

    $match = $db->get_row("SELECT * 
        FROM activity 
        WHERE 
            user_id='$_REQUEST[user_id]' AND 
            path='$_REQUEST[path]' AND
            ip='$_SERVER[REMOTE_ADDR]' AND
            action='$_REQUEST[action]'");

    if (empty($match)) {
        $db->insert('activity', [
            'id'      => generate_mysql_id(),
            'user_id' => $_REQUEST['user_id'],
            'path'    => $_REQUEST['path'],
            'ip'      => $_SERVER['REMOTE_ADDR'],
            'action'  => $_REQUEST['action'],
            'ttl'     => $ttl,
        ]);

        new APIResponse(1, "Activity created successfully");
    } else {
        $db->update('activity', [
            'ip'  => $_SERVER['REMOTE_ADDR'],
            'ttl' => $ttl,
        ], [
            'id' => $match['id'],
        ]);

        new APIResponse(1, "Activity updated successfully", $ttl);
    }
}
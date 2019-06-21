<?php

function update_geo()
{
    if (empty($_REQUEST['id'])) {
        new APIResponse(0, "Missing id");
    }
    if (empty($_REQUEST['lat'])) {
        new APIResponse(0, "Missing lat");
    }
    if (empty($_REQUEST['lng'])) {
        new APIResponse(0, "Missing lng");
    }

    $db = Database::getInstance();
    $db->update('users', [
        'lat' => $_REQUEST['lat'],
        'lng' => $_REQUEST['lng'],
    ], [
        'id' => $_REQUEST['id'],
    ]);

    new APIResponse(1, "Success");
}
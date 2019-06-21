<?php

function update_job()
{
    if (empty($_REQUEST['id'])) {
        new APIResponse(0, "Missing parameter: id");
    }

    if (empty($_REQUEST['response'])) {
        new APIResponse(0, "Missing parameter: response");
    }

    $db = Database::getInstance();

    if (!$db->update('jobs', [
        'status'   => $_REQUEST['status'] ?? 'complete',
        'response' => $_REQUEST['response'],
    ], [
        'id' => $_REQUEST['id'],
    ])) {
        new APIResponse(0, "Database Error: Unable to Complete Job!", $_REQUEST);
    }

    new APIResponse(1, "Job Updated Successfully", $_REQUEST['id']);
}
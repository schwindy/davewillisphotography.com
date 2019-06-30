<?php

function toggle_public()
{
    if(empty($_REQUEST['id']))new APIResponse(0, "Missing id");
    if(empty($_REQUEST['is_public']))new APIResponse(0, "Missing is_public");

    $db = Database::getInstance();
    $db->update
    (
        'docs',
        [
            'is_public'=>(string)$_REQUEST['is_public'],
        ],
        [
            'id'=>$_REQUEST['id'],
        ]
    );

    new APIResponse(1, "Success");
}
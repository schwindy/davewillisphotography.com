<?php

function update_bio()
{
    if(empty($_REQUEST['id']))new APIResponse(0, "Missing id");
    if(empty($_REQUEST['bio']))new APIResponse(0, "Missing bio");

    $db = Database::getInstance();

    $doc = $db->get_row("SELECT * FROM docs WHERE id='$_REQUEST[id]'");
    if(empty($doc))new APIResponse(0, "Invalid ID");

    $db->update
    (
        'docs',
        [
            'bio'=>$_REQUEST['bio'],
        ],
        [
            'id'=>$_REQUEST['id'],
        ]
    );

    new APIResponse(1, "Description changed successfully!");
}
<?php

function update_display_name()
{
    if(empty($_REQUEST['id']))new APIResponse(0, "Missing id");
    if(empty($_REQUEST['display_name']))new APIResponse(0, "Missing display_name");

    $db = Database::getInstance();

    $doc = $db->get_row("SELECT * FROM docs WHERE id='$_REQUEST[id]'");
    if(empty($doc))new APIResponse(0, "Invalid ID");

    $db->update
    (
        'docs',
        [
            'display_name'=>$_REQUEST['display_name'],
        ],
        [
            'id'=>$_REQUEST['id'],
        ]
    );

    new APIResponse(1, "Display Name changed successfully!");
}
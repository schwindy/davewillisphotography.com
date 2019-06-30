<?php

function update_tags()
{
    if(empty($_REQUEST['id']))new APIResponse(0, "Missing id");
    if(empty($_REQUEST['tags']))new APIResponse(0, "Missing tags");

    $db = Database::getInstance();

    $doc = $db->get_row("SELECT * FROM docs WHERE id='$_REQUEST[id]'");
    if(empty($doc))new APIResponse(0, "Invalid ID");

    $db->update
    (
        'docs',
        [
            'tags'=>$_REQUEST['tags'],
        ],
        [
            'id'=>$_REQUEST['id'],
        ]
    );

    new APIResponse(1, "Tags changed successfully!");
}
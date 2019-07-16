<?php

function update_category()
{
    if(empty($_REQUEST['id']))new APIResponse(0, "Missing id");
    if(empty($_REQUEST['category']))new APIResponse(0, "Missing category");

    $db = Database::getInstance();

    $doc = $db->get_row("SELECT * FROM docs WHERE id='$_REQUEST[id]'");
    if(empty($doc))new APIResponse(0, "Invalid ID");

    $db->update
    (
        'docs',
        [
            'category'=>$_REQUEST['category'],
        ],
        [
            'id'=>$_REQUEST['id'],
        ]
    );

    new APIResponse(1, "Category changed successfully!");
}
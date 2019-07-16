<?php

function update_sub_category()
{
    if(empty($_REQUEST['id']))new APIResponse(0, "Missing id");
    if(empty($_REQUEST['sub_category']))new APIResponse(0, "Missing sub_category");

    $db = Database::getInstance();

    $doc = $db->get_row("SELECT * FROM docs WHERE id='$_REQUEST[id]'");
    if(empty($doc))new APIResponse(0, "Invalid ID");

    $db->update
    (
        'docs',
        [
            'sub_category'=>$_REQUEST['sub_category'],
        ],
        [
            'id'=>$_REQUEST['id'],
        ]
    );

    new APIResponse(1, "SubCategory changed successfully!");
}
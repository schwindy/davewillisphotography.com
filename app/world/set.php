<?php

function set()
{
    ini_set("post_max_size", "4G");
    ini_set("upload_max_filesize", "4G");

    if(empty($_REQUEST['type']))new APIResponse(0, "Missing type");
    if(empty($_REQUEST['page']))new APIResponse(0, "Missing page");
    if(empty($_REQUEST['data']))new APIResponse(0, "Missing data");
    $db = Database::getInstance();

    if((int)$_REQUEST['page'] === 1)$db->delete('__world', ['type'=>$_REQUEST['type']]);

    if(!__world_set($_REQUEST["type"], $_REQUEST['page'], gzuncompress($_REQUEST['data'])))
        new APIResponse(0, "DB Error", $_REQUEST);

    new APIResponse(1, "World set successfully!");
}
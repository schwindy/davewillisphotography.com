<?php
acl_require_admin(CURRENT_PATH);
if(empty($_REQUEST['id']))redirect_to('/admin/docs/');
$row = $db->get_row("SELECT * FROM docs WHERE id='$_REQUEST[id]'");
if(empty($row))redirect_to('/admin/docs/');

$path = str_replace("//", "/", WEBROOT.$row['file_url']);
if(file_exists($path))shell_exec("rm $path -f");

$db->delete
(
    'docs',
    [
        'id'=>$_REQUEST['id'],
    ]
);

redirect_to('/admin/docs/');
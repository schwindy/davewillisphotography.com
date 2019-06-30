<?php

function upload_file()
{
    if(empty($_REQUEST['user_id']))new APIResponse(0, "Missing user_id");
    if(empty($_FILES) || empty($_FILES['upload']) || empty($_FILES['upload']['tmp_name']))
    {
        redirect_to("/admin/docs");
    }

    $db = Database::getInstance();

    $asset_id = generate_mysql_id();
    $file_ext = substr($_FILES['upload']['name'], strrpos($_FILES['upload']['name'], '.')+1);
    $file_name = $asset_id;
    $file_name_ext = "$file_name.$file_ext";
    $file_path_temp = $_FILES['upload']['tmp_name'];
    $file_path = TEMP_UPLOAD_PATH."/$file_name.$file_ext";
    $file_url = "/docs/$file_name_ext";

    if(!is_dir(TEMP_UPLOAD_PATH))mkdir(TEMP_UPLOAD_PATH);
    if(!file_exists($file_path_temp))new APIResponse(0, "Temporary file does not exist...");
    if(!move_uploaded_file($file_path_temp, $file_path))new APIResponse(0, "Failed to move temporary file...");
    if(!file_exists($file_path))new APIResponse(0, "Final file does not exist...");

    if(is_image($file_path))
    {
        $file_type = 'image';
        $image = image_auto_rotate($file_path);
        $image->writeImage($file_path);
    }
    else if(is_video($file_path))$file_type = 'video';
    else $file_type = $file_ext;

    $db->insert
    (
        'docs',
        [
            'id'=>$asset_id,
            'display_name'=>'New Doc - '.get_date(),
            'file_type'=>$file_type,
            'file_url'=>$file_url,
            'file_ext'=>$file_ext,
            'file_size'=>$_FILES['upload']['size'],
            'created'=>get_date(),
        ]
    );

    redirect_to("/admin/docs/view?id=$asset_id");
}
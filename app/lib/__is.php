<?php

function is_email($str)
{
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}

function is_image($file_path)
{
    return getimagesize($file_path) ? true : false;
}

function is_ip($str)
{
    return filter_var($str, FILTER_VALIDATE_IP);
}

function is_uploading()
{
    if (!empty($_FILES) && !empty($_FILES['upload']) && !empty($_FILES['upload']['tmp_name'])) {
        return true;
    }

    return false;
}

function is_video($file_path)
{
    $valid = [
        "application/x-mpegURL",
        "video/3gpp",
        "video/MP2T",
        "video/mpeg",
        "video/mp4",
        "video/ogg",
        "video/quicktime",
        "video/x-flv",
        "video/x-msvideo",
        "video/x-ms-wmv",
        "video/vnd.vivo",
        "video/webm",
    ];

    $mime = mime_content_type($file_path);
    foreach ($valid as $valid_mime) {
        if ($mime === $valid_mime) {
            return true;
        }
    }
    if (str_contains($mime, 'video/')) {
        return true;
    }

    return false;
}
<?php

function convert_video_to_mp4($args)
{
    $args['thread'] = empty($args['thread']) ? "" : "2>&1";

    $cmd = "mediainfo \"--Inform=Video;%Rotation%\" $args[file_path]";
    $rotate = (int)exec($cmd);
    if (empty($rotate) && $rotate !== 0) {
        $rotate = (int)shell_exec($cmd);
    }

    if ($rotate === 0) {
        $options = "-q 20 -r 30 -B 160 -vb 500 -x level=3.0 --optimize";
    } else {
        $options = "-q 20 -r 30 -B 160 -vb 500 -x level=3.0 --optimize --rotate=angle=$rotate:hflip=0";
    }

    $one = "HandBrakeCLI -i $args[file_path] -o $args[file_path_final] --preset=\"Universal\"";
    $two = "$options $args[thread]";
    $cmd = "$one $two";
    shell_exec($cmd);

    return $args['file_path_final'];
}

function does_file_exist($url, $retry = 1)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response_code === 404) {
        return false;
    }
    if ($response_code === 200 || $response_code === 304) {
        return true;
    }

    if ($retry) {
        sleep(1);

        return does_file_exist($url, 0);
    }

    return false;
}

function generate_thumbnail($args)
{
    if (!is_dir(TEMP_UPLOAD_PATH)) {
        mkdir(TEMP_UPLOAD_PATH);
    }
    if (!file_exists($args['file_path'])) {
        return ['status' => 0, 'message' => "generate_thumbnail(): Source File does not exist: $args[file_path]"];
    }

    $degrees_rotated = (int)exec('mediainfo "--Inform=Video;%Rotation%" ' . $args['file_path']);
    if ($degrees_rotated) {
        $args['rotate'] = "-vf \"transpose=1\"";
    }

    $args['options'] = empty($args['options']) ? "-an -deinterlace -an -ss 2 -f mjpeg -t 1 -r 1 -y" : $args['options'];
    $args['resolution'] = empty($args['resolution']) ? "400x300" : $args['resolution'];
    $args['rotate'] = empty($args['rotate']) ? "" : $args['rotate'];
    $args['thread'] = empty($args['thread']) ? "" : "2>&1";

    $one = "cd " . FFMPEG_PATH;
    $two = "./ffmpeg -i $args[file_path] $args[options] $args[rotate]";
    $three = "-s $args[resolution] $args[thumbnail_path] $args[thread]";
    exec("$one;$two $three;");

    // CLI Execution Fallback (shell_exec)
    if (!file_exists($args['file_path'])) {
        shell_exec("$one;$two $three;");
    }
    if (!file_exists($args['file_path'])) {
        return ['status' => 0, 'message' => "generate_thumbnail(): Thumbnail Generation Failed..."];
    }

    return $args['thumbnail_path'];
}

function get_extension($file_path)
{
    return substr($file_path, strrpos($file_path, ".") + 1);
}

function get_mime($file_path)
{
    return mime_content_type($file_path);
}

function image_auto_rotate($file_path)
{
    $image = new Imagick($file_path);
    $orientation = $image->getImageOrientation();

    switch ($orientation) {
        case imagick::ORIENTATION_BOTTOMRIGHT:
            $image->rotateimage("#000", 180); // rotate 180 degrees
            break;

        case imagick::ORIENTATION_RIGHTTOP:
            $image->rotateimage("#000", 90); // rotate 90 degrees CW
            break;

        case imagick::ORIENTATION_LEFTBOTTOM:
            $image->rotateimage("#000", -90); // rotate 90 degrees CCW
            break;
    }

    // Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
    $image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);

    return $image;
}
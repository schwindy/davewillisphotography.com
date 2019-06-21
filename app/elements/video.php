<?php

// Set Default Values
$default = [
    'created'       => $args['this']->created,
    'display_name'  => $args['this']->display_name,
    'display_bio'   => $args['this']->display_bio,
    'display_url'   => $args['this']->display_url,
    'file_url'      => $args['this']->file_url,
    'id'            => $args['this']->id,
    'thumbnail_url' => $args['this']->file_thumb_url,
];

// Apply default values & Override any/all default values if $key in __echo($args) exists
$args = array_merge($default, $args);

if (strpos($args['file_url'], "http://") === false && strpos($args['file_url'], "https://") === false) {
    $args['file_url'] = SITE_URL . $args['file_url'];
}

echo "<link href='/lib/video-js/main.css' rel='stylesheet'>
<script src='/lib/video-js/main.js'></script>
<div id='player'>
    <video 
        id='my-video' 
        class='video-js' 
        controls autoplay preload='auto' 
        poster='$args[thumbnail_url]' 
        data-setup='{}'
    ><source src='$args[file_url]' type='video/mp4'></video>
</div>";
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

echo "<div class='image_element'>
    <img src='$args[file_url]'>
</div>

<style>body{overflow: visible !important;}</style>";
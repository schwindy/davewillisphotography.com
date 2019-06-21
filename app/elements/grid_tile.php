<?php

// Set Default Values
$default = [
    'created'       => $args['this']->created,
    'display_name'  => $args['this']->display_name,
    'display_bio'   => $args['this']->display_bio,
    'display_url'   => $args['this']->display_url,
    'id'            => $args['this']->id,
    'file_type'     => $args['this']->file_type,
    'file_url'      => $args['this']->file_url,
    'thumbnail_url' => $args['this']->file_thumb_url,
    'share_count'   => $args['this']->share_count,
    'user_id'       => $args['this']->user_id,
    'created'       => $args['this']->created,
];

// Apply default values & Override any/all default values if $key in __echo($args) exists
$args = array_merge($default, $args);

$args['display_name'] = trim($args['display_name']);
$args['display_bio'] = trim($args['display_bio']);

$args['display_name'] = empty($args['display_name']) ? 'No Title' : $args['display_name'];
$args['display_bio'] = empty($args['display_bio']) ? 'No Description' : $args['display_bio'];

$thumbnail_html = "<a data-featherlight='image' href='$args[thumbnail_url]' class=''>
    <img src='$args[thumbnail_url]' class='list_tile_img' title='$args[display_name]'>
</a>";

echo "<div id='$args[id]' class='card grid_item'>
    $thumbnail_html
    <h3 class='text_center'>$args[display_name]</h3>
    <br>
    <p class='text_center'><b>$args[file_type]</b></p>
    <p class='text_center'>User: <a href='/admin/users/view?id=$args[user_id]' class='blue'>$args[user_id]</a></p>
    <p class='text_center'>AirCastIt Counter: $args[share_count]</p>
    <p class='text_center'>$args[created]</p>
    <a href='/admin/assets/view?id=$args[id]' class='text_center blue bold'><p>View Asset</p></a>
</div>";
<?php
$row = $db->get_row("SELECT * FROM elements WHERE id='$_REQUEST[id]'");
$obj = new Element($row);

if (strpos($row['type'], 'shop_item') !== false) {
    $type = substr($row['type'], 0, strpos($row['type'], '_item'));
    redirect_to("/admin/kek/$type/view?id=$row[id]");
}

echo __html('card', [
    'text' => __html('h1', ['text' => "$row[display_name]", 'class' => 'text_left']) . __html('br') . __html('p',
            "<b>Type:</b> $row[type]") . __html('p', "<b>Created:</b> $row[created]") . __html('p',
            "<b>Updated:</b> $row[updated]") . __html('p', "<b>Notes:</b> $row[notes]")
]);

echo __html('card', [
    'class' => "text_center",
    'text'  => __html('h1', "Element Preview"),
]);

echo __html('card', [
    'class' => "kek_$obj->type " . (empty($obj->properties['class']) ? "" : $obj->properties['class']),
    'text'  => __kek_decode($obj->data, $obj),
]);
?>
<script>$(function(){
        $('title').html($('title').html().replace('View', 'Element'));
    })</script>

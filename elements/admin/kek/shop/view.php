<?php
acl_require_admin(CURRENT_PATH);

echo __html('css', ['href' => '/css/pages/shop.css']);

$row = $db->get_row("SELECT * FROM elements WHERE id='$_REQUEST[id]'");
$element = new Element($row);

echo __html('card', [
    'text' => __html('h1',
            ['text' => "$row[display_name]", 'prop' => ['class' => 'text_center']]) . __html('br') . __html('p',
            ['text' => "<b>Type:</b> $row[type]"]) . __html('p',
            ['text' => "<b>Created:</b> $row[created]"]) . __html('p',
            ['text' => "<b>Updated:</b> $row[updated]"]) . __html('p', ['text' => "<b>Notes:</b> $row[notes]"])
]);

echo __html('h1', ['text' => "Shop Item Preview", 'class' => 'text_center']);

echo __html('card', [
    'prop' => ['class' => 'shop_grid'],
    'text' => __html($element->type, [
            'text'    => $element->data,
            'options' => $element->options,
            'prop'    => $element->properties,
        ]) . __html($element->type, [
            'text'    => $element->data,
            'options' => $element->options,
            'prop'    => $element->properties,
        ]) . __html($element->type, [
            'text'    => $element->data,
            'options' => $element->options,
            'prop'    => $element->properties,
        ]) . __html($element->type, [
            'text'    => $element->data,
            'options' => $element->options,
            'prop'    => $element->properties,
        ])
]);
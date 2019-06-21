<?php

function generate_shop($elements = [], $acl = '')
{
    $db = Database::getInstance();
    $html = "<div class='shop_grid'>";

    if (empty($elements)) {
        $elements = $db->get_rows("SELECT * FROM elements WHERE type='shop_item' ORDER BY display_name ASC");
    }

    foreach ($elements as $element) {
        if (is_array($element)) {
            $element = new Element($element);
        }

        if (!empty($element->properties()['acl']) && $element->properties()['acl'] !== $acl) {
            continue;
        }

        $html .= __html($element->type, [
            'text'    => $element->data,
            'options' => $element->options,
            'prop'    => $element->properties(),
            'acl'     => $acl,
        ]);
    }

    $html .= "</div>";

    return $html;
}
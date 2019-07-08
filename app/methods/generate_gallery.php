<?php

function generate_gallery($elements=[])
{
    $db = Database::getInstance();
    $html = "<div class='gallery_grid'>";

    if(empty($elements)) {
        $elements = $db->get_rows("SELECT * FROM docs WHERE is_public='true' ORDER BY created DESC");
    }

    foreach($elements as $element) {
        if(is_array($element)){$element = new Element($element);}
        $html .= __html("gallery_item", ['text'=>$element->bio, 'prop'=>(array)$element]);
    }

    $html .= "</div>";
    return $html;
}
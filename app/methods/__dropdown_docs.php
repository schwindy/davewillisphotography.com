<?php

function __dropdown_docs($args=[])
{
    $db = Database::getInstance();
    $docs = $db->get_rows("SELECT * FROM docs ORDER BY display_name ASC");
    $body = "<option name='' value=''>-- Select a Doc --</option>";

    if(empty($args['name']))$args['name'] = 'name';

    foreach($docs as $doc)
    {
        if($doc['id'] === $_REQUEST['id'])continue;
        if(empty($args['field']))$args['field'] = 'id';
        $body .= "<option name='$args[name]' value='".$doc[$args['field']]."'>$doc[display_name]</option>";
    }

    $args['class'] = empty($args['class'])?"var val":"var val $args[class]";
    return "<select id='$args[name]' name='$args[name]' class='$args[class]'>$body</select>";
}
<?php

function __dropdown_docs()
{
    $db = Database::getInstance();

    $docs = $db->get_rows("SELECT * FROM docs ORDER BY display_name ASC");

    $body = "<option name='' value=''>-- Select a Doc --</option>";
    foreach ($docs as $doc) {
        if ($doc['id'] === $_REQUEST['id']) {
            continue;
        }
        $body .= "<option name='doc_id' value='$doc[id]'>$doc[display_name]</option>";
    }

    return "<select name='doc_id' class='var val'>$body</select>";
}
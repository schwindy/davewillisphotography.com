<?php

function save_page()
{
    if (empty($_REQUEST['id'])) {
        $_ALERT[] = "Missing id";

        return false;
    }

    if (empty($_REQUEST['display_name'])) {
        $_ALERT[] = "Missing display_name";

        return false;
    }

    if (empty($_REQUEST['path'])) {
        $_ALERT[] = "Missing path";

        return false;
    }

    $db = Database::getInstance();

    $_REQUEST['object_class'] = empty($_REQUEST['object_class']) ? '' : $_REQUEST['object_class'];
    $_REQUEST['object_table'] = empty($_REQUEST['object_table']) ? '' : $_REQUEST['object_table'];
    $_REQUEST['head'] = empty($_REQUEST['head']) ? 'head' : $_REQUEST['head'];
    $_REQUEST['title'] = empty($_REQUEST['title']) ? '' : $_REQUEST['title'];
    $_REQUEST['description'] = empty($_REQUEST['description']) ? '' : $_REQUEST['description'];
    $_REQUEST['nav'] = empty($_REQUEST['nav']) ? 'nav' : $_REQUEST['nav'];
    $_REQUEST['foot'] = empty($_REQUEST['foot']) ? 'foot' : $_REQUEST['foot'];
    $_REQUEST['type'] = empty($_REQUEST['type']) ? 'kek' : $_REQUEST['type'];

    $page = $db->get_row("SELECT * FROM pages WHERE id='$_REQUEST[id]'");

    $db->update("pages", [
        'object_class' => $_REQUEST['object_class'],
        'object_table' => $_REQUEST['object_table'],
        'display_name' => $_REQUEST['display_name'],
        'path'         => $_REQUEST['path'],
        'head'         => $_REQUEST['head'],
        'title'        => $_REQUEST['title'],
        'description'  => $_REQUEST['description'],
        'nav'          => $_REQUEST['nav'],
        'body'         => $page['body'],
        'foot'         => $_REQUEST['foot'],
        'notes'        => $_REQUEST['notes'],
    ], [
        'id' => $_REQUEST['id'],
    ]);

    echo "<script>location.href = '/admin/kek/pages/view?id=$_REQUEST[id]';</script>";
}
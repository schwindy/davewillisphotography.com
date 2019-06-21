<?php

function copy_page()
{
    if (empty($_REQUEST['id'])) {
        $_ALERT[] = "Missing id";

        return false;
    }

    $db = Database::getInstance();

    $page = $db->get_row("SELECT * FROM pages WHERE id='$_REQUEST[id]'");

    $id = generate_mysql_id();

    $db->insert("pages", [
        'id'           => $id,
        'display_name' => "$page[display_name] - Copy",
        'head'         => $page['head'],
        'title'        => $page['title'],
        'nav'          => $page['nav'],
        'body'         => $page['body'],
        'foot'         => $page['foot'],
        'path'         => "$page[path]_copy",
        'created'      => get_date(null, 'Y-m-d H:i:s'),
    ]);

    echo "<script>location.href = '/admin/kek/pages/view?id=$id';</script>";
}
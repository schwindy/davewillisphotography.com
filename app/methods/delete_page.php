<?php

function delete_page()
{
    if (empty($_REQUEST['id'])) {
        $_ALERT[] = "Missing id";

        return false;
    }

    $db = Database::getInstance();

    $db->delete("pages", [
        'id' => $_REQUEST['id'],
    ]);

    echo "<script>location.href = '/admin/kek/pages';</script>";
}
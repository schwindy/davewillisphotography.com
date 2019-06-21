<?php

function copy_element($redirect = '/admin/kek/elements')
{
    if (empty($_REQUEST['id'])) {
        $_ALERT[] = "Missing id";

        return false;
    }

    $db = Database::getInstance();

    $row = $db->get_row("SELECT * FROM elements WHERE id='$_REQUEST[id]'");
    $element = new Element($row);

    $id = generate_mysql_id();

    $db->insert("elements", [
        'id'           => $id,
        'display_name' => "$row[display_name] - Copy",
        'type'         => $row['type'],
        'data'         => $row['data'],
        'options'      => $row['options'],
        'properties'   => json_encode($element->properties()),
        'notes'        => $row['notes'],
        'created'      => get_date(null, 'Y-m-d H:i:s'),
    ]);

    echo "<script>location.href = '$redirect/view?id=$id';</script>";
}
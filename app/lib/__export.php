<?php

function generate_mysql_csv($args, $options = [])
{
    $csv = '';
    $db = Database::getInstance();
    $file_name = "$args[table_name]-" . str_replace([' ', ':'], '', get_date()) . ".csv";
    $file_path = BASE_PATH . "tmp/$file_name";

    if (empty($args['data'])) {
        $args['where'] = empty($args['where']) ? '' : "WHERE $args[where]";
        $args['order_by'] = empty($args['order_by']) ? '' : "ORDER BY $args[order_by]";
        $args['limit'] = empty($args['limit']) ? '' : "LIMIT $args[limit]";
        $args['data'] = $db->query("SELECT * FROM $args[table_name] $args[where] $args[order_by]");
    }

    foreach ($args['data'] as $row) {
        $row = (array)$row;
        $sub_csv = '';

        if (empty($csv)) {
            foreach ($args['fields'] as $field) {
                $csv .= "\"$field\",";
            }
            $csv = str_lreplace(",", "", $csv);
            $csv .= $sub_csv . "\n";
        }

        foreach ($args['fields'] as $field) {
            $sub_csv .= "\"$row[$field]\",";
        }
        $sub_csv = str_lreplace(",", "", $sub_csv);
        $csv .= $sub_csv . "\n";
    }

    if (empty($csv)) {
        return $options;
    }

    file_put_contents($file_path, $csv);

    if (!is_file($file_path)) {
        echo __log("generate_mysql_csv(): CSV Generation Failed...");

        return $options;
    }

    $options['attachments'][$file_name] = $file_path;

    return $options;
}
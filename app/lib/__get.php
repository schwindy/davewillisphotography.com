<?php

function __get($table_name, $args = [])
{
    $db = Database::getInstance();

    if (is_string($args)) {
        $args = ['where' => $args];
    }
    $query = "SELECT * FROM $table_name";
    $query .= empty($args['where']) ? "" : " WHERE $args[where]";
    $query .= empty($args['order_by']) ? "" : " ORDER BY $args[order_by]";
    $query .= empty($args['limit']) ? "" : " LIMIT $args[limit]";
    $rows = $db->get_rows($query);

    $class = str_to_class(str_singular($table_name));
    if (!class_exists($class)) {
        if (count($rows) > 1) {
            return $rows;
        }
        if (empty($rows)) {
            return [];
        }

        return $rows[0];
    }

    $objects = [];
    foreach ($rows as $row) {
        if (count($rows) === 1) {
            return new $class($row);
        }
        $objects[] = new $class($row);
    }

    return $objects;
}

function __get_all($table_name, $args = [])
{
    $db = Database::getInstance();

    if (is_string($args)) {
        $args = ['where' => $args];
    }
    $query = "SELECT * FROM $table_name";
    $query .= empty($args['where']) ? "" : " WHERE $args[where]";
    $query .= empty($args['order_by']) ? "" : " ORDER BY $args[order_by]";
    $query .= empty($args['limit']) ? "" : " LIMIT $args[limit]";
    $rows = $db->get_rows($query);

    $class = str_to_class(str_singular($table_name));
    if (!class_exists($class)) {
        return $rows;
    }

    $objects = [];
    foreach ($rows as $row) {
        $objects[] = new $class($row);
    }

    return $objects;
}

function __get_first($table_name, $args = [])
{
    if (is_string($args)) {
        $args = ['where' => $args];
    }
    $args['limit'] = "1";

    return __get($table_name, $args);
}
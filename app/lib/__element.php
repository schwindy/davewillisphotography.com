<?php
/** Generate an Element
 * @param string $path
 * @param array $args
 *  $args:array - Array of variable names (key) and variable values (val) to be made available to Element Scope
 * @return bool
 */
function __element($path = '', $args = [])
{
    static $_ALERT = [];
    static $_LOG = [];
    $obj = [];

    if (empty($_REQUEST['id'])) {
        $_REQUEST['id'] = '';
    }

    // Iterate Through Multiple Paths If $path Is An Array Of Element IDs
    if (is_array($path)) {
        foreach ($path as $val) {
            __element($val, $args);
        }

        return true;
    }

    $db = Database::getInstance();

    if (empty($path)) {
        $path = CURRENT_PATH;
    }
    if (substr($path, 0, 1) === '/') {
        $path = substr($path, 1);
    }

    // Make Element Scope Mimic $args
    if (empty($args['user'])) {
        $args['user'] = get_user();
    }
    if (!empty($args)) {
        foreach ($args as $key => $val) {
            $$key = $val;
        }
    }

    $path = str_replace('.php', '', $path);
    $file_name = "$path.php";
    $file_path = BASE_PATH . "elements/$file_name";

    // Autoload $path CSS (if exists)
    $file = "/css/pages/$path.css";
    if (file_exists(WEBROOT . $file)) {
        echo "<link href='$file' rel='stylesheet'>";
    }

    // Autoload $path Javascript (if exists)
    $file = "/js/pages/$path.js";
    if (file_exists(WEBROOT . $file)) {
        echo "<script src='$file'></script>";
    }

    // Autoload CURRENT_PAGE Menu (if exists)
    $menu_path = str_replace(".php", "_menu.php", $file_path);
    if (file_exists($menu_path)) {
        include($menu_path);
    }

    // Load Main Element
    if (file_exists($file_path)) {
        include($file_path);
    }

    $row = $db->get_row("SELECT * FROM elements WHERE id='$path'");
    if (!empty($row)) {
        $element = new Element($row);
        if (!empty($element->properties['object'])) {
            $obj = __get($element->properties['object'], "id='$_REQUEST[id]'");
        }
        echo __html($element->type, ['text' => __kek_decode($element->data, $obj), 'prop' => $element->properties]);
    }

    foreach ($_ALERT as $alert) {
        echo __alert($alert);
    }
    foreach ($_LOG as $log) {
        echo __log("$row[display_name]: " . $log[0], $log[1]);
    }

    return true;
}
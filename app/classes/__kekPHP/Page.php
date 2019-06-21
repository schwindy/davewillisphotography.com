<?php

class Page extends BaseClass
{
    var $body = 'json_array';
    var $object;

    function __construct($array = [])
    {
        parent::__construct($array);
    }

    /** Return HTML Page Description
     * @param array $routes
     * @return string
     */
    function description($routes = [])
    {
        $desc = empty($this->description) ? "" : $this->description;
        if (strpos($desc, '<meta') !== false) {
            return __kek_decode($desc, $this->object);
        }

        $desc = __kek_decode($desc, $this->object());

        return "<meta name='description' content='$desc'>";
    }

    /** Return HTML Foot Element
     * @param array $routes
     * @return string
     */
    function foot($routes = [])
    {
        if (!empty($routes) && !empty($routes[0]) && $routes[0] === 'admin') {
            return ['admin/foot'];
        }
        if (empty($this->foot)) {
            return ['foot'];
        }

        return $this->foot;
    }

    /** Return HTML Head Element
     * @param array $routes
     * @return string
     */
    function head($routes = [])
    {
        if (!empty($routes) && !empty($routes[0]) && $routes[0] === 'admin') {
            return ['head', 'admin/head'];
        }
        if (empty($this->head)) {
            return 'head';
        }

        return $this->head;
    }

    /** Return HTML Nav Element
     * @param array $routes
     * @return string
     */
    function nav($routes = [])
    {
        if (!empty($routes) && !empty($routes[0]) && $routes[0] === 'admin') {
            return 'admin/nav';
        }
        if (empty($this->nav)) {
            return 'nav';
        }

        return $this->nav;
    }

    /** Return Page Object
     * @return string
     * @return object|bool (false means Page Object does not exist)
     */
    function object()
    {
        $db = Database::getInstance();
        if (empty($this->object_table) || empty($this->object_class)) {
            return false;
        }

        $row = $db->get_row("SELECT * FROM $this->object_table WHERE id='$_REQUEST[id]'");
        if (empty($row)) {
            return false;
        }

        if (!class_exists($this->object_class)) {
            return __log("Page Error: Class does not exist($this->object_class)");
        }

        return new $this->object_class($row);
    }

    /** Return HTML Page Title
     * @param array $routes
     * @return string
     */
    function title($routes = [])
    {
        $title = empty($this->title) ? ucwords(str_replace("_", " ", CURRENT_PAGE)) : $this->title;
        if (strpos($title, '| ' . SITE_NAME) !== false) {
            return __kek_decode($title, $this->object);
        }

        $title = __kek_decode($title, $this->object());

        return "$title | " . SITE_NAME;
    }
}
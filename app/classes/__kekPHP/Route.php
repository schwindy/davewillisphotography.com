<?php

class Route extends BaseClass
{
    var $account;
    var $acl;
    var $current_page = CURRENT_PAGE;
    var $current_path = CURRENT_PATH;
    var $home;
    var $routes;

    function __construct($routes = [])
    {
        parent::__construct(['routes' => $routes]);
    }

    /** Return the current Account Path (relative to SITE_URL)
     * @param array $routes (optional, defaults to $this->routes)
     * @return string $path
     */
    function account($routes = [])
    {
        if (empty($routes)) {
            $routes = $this->routes;
        }
        if (empty($routes)) {
            return '/account';
        }
        if ($routes[0] !== 'user' && $routes[0] !== 'admin') {
            return '/account';
        }

        return "/$routes[0]/account";
    }

    /** Return the current ACL ($routes[0])
     * @param array $routes (optional, defaults to $this->routes)
     * @return string $acl
     */
    function acl($routes = [])
    {
        if (empty($routes)) {
            $routes = $this->routes;
        }
        if (empty($routes)) {
            return '';
        }
        if (CURRENT_PAGE === 'login') {
            return '';
        }

        return $routes[0];
    }

    /** Return the current Home Path (relative to SITE_URL)
     * If CURRENT_PAGE !== index, Home = __DIR__
     * Else If CURRENT_PAGE === index, Home = __DIR__ ../
     * @param array $routes (optional, defaults to $this->routes)
     * @return string $path
     */
    function home($routes = [])
    {
        if (empty($routes)) {
            $routes = $this->routes;
        }
        if (empty($routes)) {
            return '/';
        }
        if (count($routes) === 1) {
            $user = get_user();
            if (!empty($user) && count($routes) === 1) {
                $routes = [0 => $user['type']];
            }
            if ($routes[0] === 'admin' || $routes[0] === 'user') {
                return "/$routes[0]/";
            }

            return "/";
        }

        $routes[count($routes) - 1] = '';

        return $this->to_path($routes);
    }

    /** Converts $routes to a path relative to SITE_URL
     * @param array $routes (optional, defaults to $this->routes)
     * @return string $path
     */
    function to_path($routes = [])
    {
        if (empty($routes)) {
            $routes = $this->routes;
        }
        if (empty($routes)) {
            return 'index';
        }

        $path = "/";
        foreach ($routes as $route) {
            if (empty($route) || $route === 'index') {
                continue;
            }
            $path .= "$route/";
        }

        return $path;
    }
}
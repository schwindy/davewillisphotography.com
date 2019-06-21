<?php
static $_ALERT = [];
static $_LOG = [];

function __kekPHP_echo($html)
{
    // Catch Errors that occurred during the Router and move them to ensure a valid HTML document :D
    $errors = '';
    $html_start = strpos($html, "<!DOCTYPE html>");
    if ($html_start !== 0) {
        $errors = substr($html, 0, $html_start);
        $html = substr($html, $html_start);
    }

    echo $html;
    echo $errors;

    // Check if kek.js loaded successfully
    if (empty(get_global_val("status"))) {
        echo "
        <script>
        function empty(obj)
        {
            return is_empty(obj);
        }
        
        function is_empty(obj)
        {
            if(obj === undefined) return true;
            if(typeof obj === 'undefined')return true;
            if(typeof obj === 'function')return false;
        
            if(typeof obj === 'string')return obj.trim()==='';
        
            if(typeof obj === 'number' && obj !== 0)return false;
            if(obj === false)return true;
            if(obj === null)return true;
        
            if(typeof obj === 'array')
            {
                if(obj.length < 1)return true;
                return false;
            }
        
            if(typeof jQuery !== 'undefined' && typeof obj === 'object')return jQuery.isEmptyObject(obj);
            return false;
        }
        
        function is_json(str)
        {
            if(typeof str === 'string' && str.indexOf('{') !== -1 && str.indexOf('}') !== -1)return true;
            return false;
        }
        
        function log(message, data)
        {
            data = empty(data)?{}:data;
            if(empty(data))console.log(message);
            else
            {
                try
                {
                    if(is_json(data))data = JSON.parse(data);
                }
                catch(e)
                {
                    console.error(\"kekPHP|> JSON Parse | Kek.log(): \"+e, data);
                    return false;
                }

                var level = empty(data['log_type'])?'log':data['log_type'];
                if(level === 'error')return console.error(\"kekPHP(error)|> \"+message, data);
                if(level === 'warn')return console.warn(\"kekPHP(warn)|> \"+message, data);
                if(level === 'info')return console.info(\"kekPHP|> \"+message, data);
                return console.log(\"kekPHP|> \"+message, data);
            }
        }
        
        for(var key in __logs)log(__logs[key].message, __logs[key].data);
        </script>";
    }
}

function __kekPHP_router()
{
    require('bootstrap.php');
    bootstrap();

    $route = '/';
    $routes = [];

    // Parse Request
    $url_components = explode('/', $_SERVER['REQUEST_URI']);
    foreach ($url_components as $key => $val) {
        if (empty($val)) {
            if (!$key) {
                continue;
            }
            $route .= 'index/';
            continue;
        }

        $routes[] = $val;
        $route .= "$val/";
    }

    // Clean Route
    $route = trim(str_lreplace("/", "", $route));
    if (empty($route) || empty($routes)) {
        $route = '/index';
    }

    // Enforce SITE_PROTOCOL (http | https)
    if (__config('SITE_PROTOCOL') === 'https') {
        $_SESSION['https_force_redirect'] = true;
    }

    if (!empty($_SERVER['REDIRECT_HTTPS']) && $_SERVER['REDIRECT_HTTPS'] !== 'on') {
        if ($_SERVER["SERVER_PORT"] == '80' && __config('SITE_PROTOCOL') === 'https') {
            http_response_code(301);
            redirect_to(__config('SITE_PROTOCOL') . '://' . __config('SITE_URL_NAME') . ($route === '/index' ? '' : $route));
            exit;
        }
    }

    // Initialize Database & Get User
    $db = Database::getInstance();
    if (empty($db) || empty($db->mysqli->client_version)) {
        return new Response(1, "Cannot connect to database...", $db);
    }
    $user = get_user();
    $_SESSION['user_id'] = empty($user) ? 'anon' : $user['id'];

    // Portal/World Router Bypass (avoid activity)
    if (strpos($route, '/php/cron') !== false) {
        return __element("cron");
    }
    if (strpos($route, '/php/portal') !== false) {
        return __element("portal");
    }
    if (strpos($route, '/php/world') !== false) {
        return __element("world");
    }

    $_r = json_encode(new __Object($_REQUEST));

    // Store User Activity
    $match = $db->get_row("SELECT * FROM activity WHERE 
            user_id='$_SESSION[user_id]' AND 
            path='$route' AND
            ip='$_SERVER[REMOTE_ADDR]' AND
            _r='$_r' AND
            action='view'");
    if (empty($match)) {
        $db->insert('activity', [
            'id'            => generate_mysql_id(),
            'action'        => 'view',
            'ip'            => $_SERVER['REMOTE_ADDR'],
            'path'          => $route,
            'ttl'           => get_date('+5 minutes'),
            'user_agent'    => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'http_referrer' => $_SERVER['HTTP_REFERER'] ?? null,
            '_r'            => $_r,
            'user_id'       => $_SESSION['user_id'],
        ]);
    } else {
        $db->update('activity', [
            'path'          => $route,
            'ttl'           => get_date('+5 minutes'),
            'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
            'http_referrer' => $_SERVER['HTTP_REFERER'] ?? null,
            'user_id'       => $_SESSION['user_id'],
            '_r'            => $_r,
        ], [
            'id' => $match['id'],
        ]);
    }

    // Force Router Bypass (on desired paths)
    if (strpos($route, '/css/__kek.min.css') !== false) {
        return __element("__kek.min.css");
    }
    if (strpos($route, '/js/__kek.min.js') !== false) {
        return __element("__kek.min.js");
    }
    if (strpos($route, '/logout') !== false) {
        return __element("logout");
    }
    if (strpos($route, '/sandbox') !== false) {
        return __element("sandbox");
    }

    // 404 Magic
    $route_element_path = ELEMENTS_PATH . substr($route, 1);
    if (!file_exists("$route_element_path.php")) {
        $page = new Page($db->get_row("SELECT * FROM pages WHERE path='$route'"));
        if (file_exists("$route_element_path/index.php")) {
            $route .= "/index";
        } else {
            if (!empty($page->body['elements'])) {
                foreach ($page->body['elements'] as $path) {
                    if (!file_exists(ELEMENTS_PATH . "$path.php")) {
                        $element = $db->get_row("SELECT * FROM elements WHERE id='$path'");
                        if (!empty($element)) {
                            continue;
                        }

                        $route = '/index';
                    }
                }
            }
        }
    }

    // Clean Route (Remove URL Parameters)
    if (strpos($route, '?') !== false) {
        $route = substr($route, 0, strrpos($route, '?'));
    }

    $current_page = str_replace(".php", "", substr($route, strrpos($route, '/') + 1));
    if ($route === '/index') {
        $routes = [];
    }

    // Define kekPHP Globals
    define('CURRENT_ROUTE', $route);
    define('CURRENT_PATH', $route);
    define('CURRENT_PAGE', $current_page);

    $page = new Page($db->get_row("SELECT * FROM pages WHERE path='$route'"));
    $route = new Route($routes);

    // Enforce ACL (if exists)
    if (!empty($route->acl)) {
        $acl_method = "acl_require_$route->acl";
        if (function_exists($acl_method)) {
            call_user_func($acl_method, CURRENT_PATH);
        }
    }

    // Set Element Scope
    $scope = [
        'obj'   => $page->object,
        'page'  => $page,
        'route' => $route,
    ];

    // Render HTML Document
    echo "<!DOCTYPE html><html lang='en'>";
    echo "<head><script>var __logs = [];</script>";
    echo "<title>" . $page->title($routes) . "</title>";
    if (!empty($page->description)) {
        echo $page->description($page->object);
    }

    __element($page->head($routes), $scope);
    echo "</head><body>";
    __element($page->nav($routes), $scope);
    if (!empty($page->body['elements'])) {
        foreach ($page->body['elements'] as $path) {
            __element($path, $scope);
        }
    }

    __element(CURRENT_ROUTE, $scope);
    __element($page->foot($routes), $scope);
    require_once('logging.php');
    echo "</body></html>";

    return new Response(1, "Initialized Successfully!");
}

/* Welcome, Skippy */
try {
    ob_start();
    __kekPHP_router();
    __kekPHP_echo(ob_get_clean());
    unset($html);
    gc_force();
    exit;
} catch (Exception $e) {
    echo vpre(new Response(1, "Router | FAILURE", $e));
}
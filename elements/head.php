<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
<meta name="keywords" content="<?php echo __config('SITE_KEYWORDS') ?>">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#2E7DB5">
<meta name="msvalidate.01" content="42B47A9A96E1E4FE6D5B5DE05A954047">
<link rel="canonical" href="<?php
$query = "";
if (!empty($_REQUEST['id'])) {
    $query .= (empty($query) ? "?" : "&") . "id=$_REQUEST[id]";
}
if (!empty($_REQUEST['p'])) {
    $query .= (empty($query) ? "?" : "&") . "p=$_REQUEST[p]";
}
if (!empty($_REQUEST['q'])) {
    $query .= (empty($query) ? "?" : "&") . "q=$_REQUEST[q]";
}
if (empty($query)) {
    $route = empty(CURRENT_ROUTE) || CURRENT_ROUTE == '/index' ? "" : CURRENT_ROUTE;
} else {
    $route = empty(CURRENT_ROUTE) || CURRENT_ROUTE == '/' ? "/index" : CURRENT_ROUTE;
}

$url = __config('SITE_URL');
$url .= $route;
$url .= $query;
echo $url ?>" itemprop="url">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700' rel='stylesheet'>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<?php
// Run CSS/JS Minification
$readCmd = KEK_OS == 'WIN' ? "type" : "cat";

if (get_global_val("Kek.minify") === "true" || !empty($_REQUEST['minify'])) {
    // Autoload kekCSS
    $css = "";
    $load_first = ["reset.css"];
    foreach ($load_first as $file) {
        $path = WEBROOT . "css" . DS . "app" . DS . $file;
        if (!file_exists($path)) {
            continue;
        }
        $css .= shell_exec("$readCmd $path");
    }

    // Autoload kekCSS Application
    foreach (glob(WEBROOT . "css" . DS . "app" . DS . "*.css") as $file) {
        $file = substr($file, strrpos($file, DS) + 1);
        if (!empty($load_first[$file])) {
            continue;
        }
        $path = WEBROOT . "css" . DS . "app" . DS . $file;
        if (!file_exists($path)) {
            continue;
        }
        $css .= shell_exec("$readCmd $path");
    }

    // Autoload kekCSS Libraries
    $css_libs = ["__kekPHP", "_max", "_media", "_min"];
    foreach ($css_libs as $lib) {
        foreach (glob(WEBROOT . "css" . DS . "lib" . DS . $lib . DS . "*.css") as $file) {
            $file = substr($file, strrpos($file, DS) + 1);
            if (!empty($load_first[$file])) {
                continue;
            }
            $path = WEBROOT . "css" . DS . "lib" . DS . $lib . DS . $file;
            $css .= shell_exec("$readCmd $path");
        }
    }

    // Autoload kekCSS Library
    foreach (glob(WEBROOT . "css" . DS . "lib" . DS . "*.css") as $file) {
        $file = substr($file, strrpos($file, DS) + 1);
        if (!empty($load_first[$file])) {
            continue;
        }
        $path = WEBROOT . "css" . DS . "lib" . DS . $file;
        if (!file_exists($path)) {
            continue;
        }
        $css .= shell_exec("$readCmd $path");
    }

    // Autoload kekJS
    $js = "";
    $js_classes = "";
    $js_config = "";
    $js_lib = "";
    $js_app = "";

    // Autoload kekJS Configuration
    foreach (glob(WEBROOT . "js" . DS . "config" . DS . "*.js") as $file) {
        $file = substr($file, strrpos($file, DS) + 1);
        $path = WEBROOT . "js" . DS . "config" . DS . $file;
        if (!file_exists($path)) {
            continue;
        }
        $content = shell_exec("$readCmd $path");
        if (strpos($path, ".min.js") === false) {
            $content = trim(minify_js(trim($content)));
        }
        $js_config .= $content;
    }

    // Autoload kekJS Library Dependencies
    $path = WEBROOT . "js" . DS . "lib" . DS . "depends" . DS . "*.js";
    $content = shell_exec("$readCmd $path");
    $js .= $content;

    // Autoload kekJS Classes
    $path = WEBROOT . "js" . DS . "classes" . DS;
    foreach (glob(WEBROOT . "js" . DS . "classes" . DS . "*.php") as $file_path) {
        $file = substr($file_path, strrpos($file_path, DS) + 1);
        $file_name = substr($file, 0, strrpos($file, '.'));
        $file_type = substr($file_path, strrpos($file_path, '.') + 1);

        if ($file_type === 'php') {
            ob_start();
            include($file_path);
            $css_path = "css" . DS . "classes" . DS . "$file_name.css";
            $css_path = WEBROOT . $css_path;
            if (file_exists($css_path)) {
                $css .= shell_exec("$readCmd $css_path");
            }

            $content = ob_get_clean();
            $content = preg_replace('/<\/script>/i', '', $content);
            $content = preg_replace('/<script>/i', '', $content);
            $content = preg_replace('/\\t/i', '', $content);
            if (strpos($file_path, ".min.js") === false) {
                $content = trim(minify_js(trim($content)));
            }
            $js_classes .= $content;
            continue;
        }

        if (!file_exists($file_path) && is_file($file_path)) {
            continue;
        }
        $content = shell_exec("$readCmd $file_path");
        $js_classes .= $content;
    }

    // Autoload kekJS Library
    foreach (glob(WEBROOT . "js" . DS . "lib" . DS . "*.js") as $file) {
        $file = substr($file, strrpos($file, DS) + 1);
        $path = WEBROOT . "js" . DS . "lib" . DS . $file;
        if (!file_exists($path)) {
            continue;
        }
        $content = shell_exec("$readCmd $path");
        if (strpos($file, ".min.js") === false) {
            $content = minify_js($content);
        }
        $js_lib .= $content;
    }

    // Autoload Application JS
    foreach (glob(WEBROOT . "js" . DS . "app" . DS . "*.js") as $file) {
        $file = substr($file, strrpos($file, DS) + 1);
        $path = WEBROOT . "js" . DS . "app" . DS . $file;
        if (!file_exists($path)) {
            continue;
        }
        $content = shell_exec("$readCmd $path");
        if (strpos($path, ".min.js") === false) {
            $content = minify_js($content);
        }
        $js_app .= $content;
    }

    set_global_val("Kek.min.css", $css);
    set_global_val("Kek.min.js", json_encode(trim($js)));
    set_global_val("Kek.min.js.app", json_encode(trim($js_app)));
    set_global_val("Kek.min.js.classes", json_encode(trim($js_classes)));
    set_global_val("Kek.min.js.config", json_encode(trim($js_config)));
    set_global_val("Kek.min.js.lib", json_encode(trim($js_lib)));
}

// Autoload kekJS Class Dependencies
$path = WEBROOT . "js/classes/depends/";
foreach (array_merge(glob("$path*.js"), glob("$path*.php")) as $file_path) {
    $file = substr($file_path, strrpos($file_path, '/') + 1);
    $file_name = substr($file, 0, strrpos($file, '.'));
    $file_type = substr($file_path, strrpos($file_path, '.') + 1);

    if ($file_type === 'php') {
        ob_start();
        include($file_path);
        $css_path = "css/classes/depends/$file_name.css";
        $css_path = WEBROOT . $css_path;
        if (file_exists($css_path)) {
            $css .= shell_exec("$readCmd $css_path");
        }

        $content = ob_get_clean();
        $content = preg_replace('/<\/script>/i', '', $content);
        $content = preg_replace('/<script>/i', '', $content);
        $content = preg_replace('/\\t/i', '', $content);
        if (strpos($file_path, ".min.js") === false) {
            $content = trim(minify_js(trim($content)));
        }
        echo "<script>" . $content . "</script>";
        continue;
    }

    $path = WEBROOT . "js/classes/$file";
    if (!file_exists($path)) {
        continue;
    }
    $content = shell_exec("$readCmd $path");
    if (strpos($file_path, ".min.js") === false) {
        $content = trim(minify_js(trim($content)));
    }
    echo "<script>$content</script>";
}

echo "<script src='" . JS_PATH . "__kek.min.js'></script>";
echo "<link rel='stylesheet' href='" . CSS_PATH . "__kek.min.css'>";

// Autoload Root JS (Browser Loading)
foreach (glob(WEBROOT . "js/*.js") as $file) {
    $file = substr($file, strrpos($file, '/') + 1);
    if (!empty($load_first[$file])) {
        continue;
    }
    $path = WEBROOT . "js/$file";
    if (!file_exists($path)) {
        continue;
    }
    echo "<script src='" . JS_PATH . "$file'></script>";
}

// Retrieve $user
$user = get_user();

// Ensure $_SESSION is initialized
if (empty($_SESSION)) {
    $_SESSION['kek'] = 'kek';
}
?>
<script>
    (function(i, s, o, g, r, a, m){
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function(){
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1*new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
    ga('create', '<?php echo __config('GOOGLE_ANALYTICS_ID')?>', 'auto');
    ga('send', 'pageview');
</script>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<script src="/lib/owl-carousel/owl.carousel.js"></script>
<link href="/lib/owl-carousel/owl.carousel.css" rel="stylesheet">
<link href="/lib/owl-carousel/owl.theme.css" rel="stylesheet">
<link href="/lib/owl-carousel/owl.transitions.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.2/velocity.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.2/velocity.ui.min.js"></script>
<script>
    var url = location.href;
    if('<?php echo __config('SITE_PROTOCOL')?>'==='https')
    {
        if(location.protocol==="http:") location.href = "https://"+url.substr(url.indexOf('://')+'://'.length);
    }
</script>
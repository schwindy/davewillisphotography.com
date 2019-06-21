<?php
/** Create an HTML5 Element
 * @param string $type
 * @param array $args (optional)
 *  $args['function']:string (optional) - Name of the function to run to generate the Element (ex: '__html_table')
 *  $args['prop']:array - Array of Element Properties (ex: ['class'=>'text_center', 'href'=>$url])
 *  $args['text']:string - Text to place inside of the open and close tags (aka: innerHTML)
 *  $args['type']:string - Element Name (ex: Element <table></table> has an Element Name of "table")
 * @return string $html - HTML5 Element
 */
function __html($type = 'table', $args = [])
{
    if (is_string($args)) {
        $data = $args;
        $args = [];
        if ($type === 'css' || $type === 'js') {
            $args['src'] = $data;
        }
        if ($type === 'h1' || $type === 'h2' || $type === 'h3' || $type === 'h4' || $type === 'h5') {
            $args['text'] = $data;
        }
        if ($type === 'p') {
            $args['text'] = $data;
        }
    }

    if ($type == "script") {
        $args = ['prop' => $args];
    }

    $args['prop'] = empty($args['prop']) ? [] : $args['prop'];
    $args['text'] = empty($args['text']) ? '' : $args['text'];
    $args['type'] = empty($type) ? $args['type'] : $type;
    $args['function'] = $args['function'] ?? "__html_$args[type]";
    $args['function'] = function_exists($args['function']) ? $args['function'] : "__html_";
    $element = call_user_func($args['function'], $args);
    if (empty($element['type'])) {
        return false;
    }

    $element['html'] = trim($element['html']);
    $element['after'] = empty($element['after']) ? "" : $element['after'];
    $element['before'] = empty($element['before']) ? "" : $element['before'];

    $properties = __html_element_to_properties($element);
    $js = __html_js($args);
    $html = "$element[before]<$element[type] $properties>$element[html]</$element[type]>$element[after]$js";
    if (!empty($element['self_closing']) && $element['self_closing']) {
        $html = str_replace("</$element[type]>", "", $html);
    }

    return $html;
}

function __html_($args = [])
{
    return [
        'id'    => empty($args['id']) ? "" : $args['id'],
        'class' => empty($args['class']) ? "" : $args['class'],
        'html'  => $args['text'],
        'prop'  => $args['prop'],
        'type'  => empty($args['type']) ? 'p' : $args['type'],
    ];
}

function __html_br($args = [])
{
    return [
        'html'         => $args['text'],
        'prop'         => $args['prop'],
        'self_closing' => true,
        'type'         => "br",
    ];
}

function __html_button($args = [])
{
    $args['id'] = empty($args['id']) ? "kek_button_" . generate_mysql_id(8) : $args['id'];
    $args['class_default'] = __html_element_default($args['type'], 'class', '__button hover_opacity');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['color_default'] = __html_element_default($args['type'], 'color', 'blue');
    $args['color'] = empty($args['color']) ? $args['color_default'] : $args['color_default'] . " $args[color]";

    $args['icon'] = empty($args['icon']) && $args['icon'] !== '' ? 'input' : $args['icon'];
    $args['text'] = empty($args['text']) ? 'Save' : $args['text'];
    $args['prop']['title'] = $args['text'];
    $args['prop']['id'] = empty($args['id']) ? "kek_button_" . generate_mysql_id(8) : $args['id'];
    $args['prop']['href'] = empty($args['href']) ? '' : $args['href'];
    $args['prop']['class'] =
        empty($args['prop']['class']) ? "$args[color]_bg " . $args['class'] : $args['prop']['class'];

    $args['text'] = "<i class='material-icons white'>$args[icon]</i><h2 class='white bold'>$args[text]</h2>";

    return [
        'type' => "a",
        'html' => $args['text'],
        'prop' => $args['prop'],
    ];
}

function __html_card($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'card');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];
    if (empty($args['prop']['id'])) {
        $args['prop']['id'] = empty($args['id']) ? "" : $args['id'];
    }

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => 'div',
    ];
}

function __html_css($args = [])
{
    $args['src'] = empty($args['src']) ? '' : $args['src'];
    $args['src'] = empty($args['href']) ? $args['src'] : $args['href'];
    $args['prop']['href'] = empty($args['src']) ? $args['prop']['href'] : $args['src'];
    $args['prop']['rel'] = 'stylesheet';

    return [
        'html'         => $args['text'],
        'prop'         => $args['prop'],
        'type'         => "link",
        'self_closing' => true
    ];
}

function __html_edit_button($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', '__edit_button');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['icon'] = empty($args['icon']) ? 'keyboard_arrow_right' : $args['icon'];
    $args['icon'] = $args['icon'] === 'none' ? '' : $args['icon'];
    $args['val'] = empty($args['val']) && $args['val'] !== '' ? $args['obj']->$args['field'] : $args['val'];
    $args['text'] = empty($args['var']) ? "<span>$args[val]</span>" : "<span><b>$args[var]:</b> $args[val]</span>";
    $args['text'] .= empty($args['icon']) ? "" : "<i class='material-icons'>$args[icon]</i>";

    $args['prop']['__id'] = $args['obj']->id;
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];
    $args['prop']['id'] = $args['field'];
    $args['prop']['input_default'] = empty($args['input_default']) ? "$args[val]" : $args['input_default'];
    $args['prop']['run'] = $args['run'];
    $args['prop']['subtext'] = empty($args['subtext']) ? '' : $args['subtext'];
    $args['prop']['title'] = empty($args['title']) ? 'Insert Title' : $args['title'];

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "p",
    ];
}

function __html_element_default($type, $property, $default = '')
{
    $config = __config("HTML.$type.$property");
    $result = empty($config) ? $default : $config;
    if (empty($config)) {
        __config("HTML.$type.$property", $default);
    }

    return $result;
}

function __html_element_to_properties($element = [])
{
    $properties = "";
    foreach ($element['prop'] as $name => $val) {
        if (empty($val)) {
            continue;
        }
        $properties .= "$name='$val' ";
    }

    return trim($properties);
}

function __html_form($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'kek_form');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    $args['prop']['action'] = empty($args['action']) ? "" : $args['action'];
    $args['prop']['action'] = empty($args['prop']['action']) ? CURRENT_PATH : $args['prop']['action'];
    $args['prop']['method'] = empty($args['method']) ? $args['prop']['method'] : $args['method'];
    $args['prop']['method'] = empty($args['prop']['method']) ? 'get' : $args['prop']['method'];

    $args['title'] = empty($args['title']) ? "" : "<h2 class='text_center bold'>$args[title]</h2>";
    $args['subtitle'] = empty($args['subtitle']) ? "" : "<h4 class='text_center bold'>$args[subtitle]</h4>";
    $args['text'] = empty($args['text']) ? "" : $args[text];
    $args['before'] = "<div class='card'>$args[title]$args[subtitle]$args[text]<br>";
    $args['after'] = "</div>";

    $fields = '';
    foreach ($args['fields'] as $field_name => $field) {
        $field['prop']['id'] = empty($field['id']) ? $field['prop']['id'] : $field['id'];
        $field['prop']['id'] = empty($field['prop']['id']) ? $field_name : $field['prop']['id'];

        $field['prop']['class'] = empty($field['class']) ? $field['prop']['class'] : $field['class'];
        $field['prop']['name'] = empty($field['name']) ? $field['prop']['name'] : $field['name'];
        $field['prop']['placeholder'] =
            empty($field['placeholder']) ? $field['prop']['placeholder'] : $field['placeholder'];
        $field['prop']['type'] = empty($field['type']) ? $field['prop']['type'] : $field['type'];
        $field['prop']['value'] = empty($field['value']) ? $field['prop']['value'] : $field['value'];

        $field['prop']['class'] = empty($field['class']) ? "text_center" : $field['class'];
        $field['prop']['name'] = empty($field['name']) ? $field_name : $field['name'];
        $field['prop']['placeholder'] = empty($field['placeholder']) ? "" : $field['placeholder'];
        $field['prop']['type'] = empty($field['type']) ? "" : $field['type'];
        $field['prop']['value'] = empty($field['value']) ? "" : $field['value'];

        if (empty($field['title']) && !empty($field['placeholder'])) {
            $field['title'] = $field['placeholder'];
        }

        $fields .= "<p class='bold'>$field[title]</p>";

        if ($field['type'] === 'textarea') {
            $field['prop']['value'] = '';
            $properties = __html_element_to_properties($field);
            $fields .= "<textarea $properties>$field[value]</textarea>";
            continue;
        }

        $properties = __html_element_to_properties($field);
        $fields .= "<input $properties>";
    }

    $submit_button = "<br><button id='submit' type='submit' title='Submit' class='hover_opacity transition'>
        <div class='padding_sm_y border_0 blue_bg'>
            <i class='material-icons white'>input</i>
            <h2 class='white bold'>Submit</h2>
        </div>
    </button>";

    $args['html'] = $fields . $submit_button;

    return [
        'html'   => $args['html'],
        'before' => $args['before'],
        'after'  => $args['after'],
        'prop'   => $args['prop'],
        'type'   => $args['type'],
    ];
}

function __html_gallery_item($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'card gallery_item');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    $args['prop']['id'] = empty($args['prop']['id']) ? '' : $args['prop']['id'];
    $prop = $args['prop'];

    $args['text'] = "<a href='$prop[image]' data-featherlight='image'><img class='gallery_item_thumbnail' src='$prop[image]'></a>
    <h3 class='gallery_item_title'>$prop[display_name]</h3>
    <p class='gallery_item_bio'>$prop[bio]</p>";

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "div",
    ];
}

function __html_h1($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'bold');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "h1",
    ];
}

function __html_h2($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'bold');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "h2",
    ];
}

function __html_h3($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'bold');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "h3",
    ];
}

function __html_h4($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', '');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "h4",
    ];
}

function __html_h5($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', '');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "h5",
    ];
}

function __html_img($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'kek_image');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    $args['src'] = is_string($args) ? $args : $args['src'];
    $args['src'] = empty($args['href']) ? $args['src'] : $args['href'];
    $args['prop']['src'] = empty($args['src']) ? $args['prop']['src'] : $args['src'];

    return [
        'html'         => $args['text'],
        'prop'         => $args['prop'],
        'self_closing' => true,
        'type'         => "img",
    ];
}

// Inject Javascript :D
function __html_js($args = [])
{
    if (empty($args['_js'])) {
        return "";
    }

    $js = "";
    $_js = $args['_js'];
    if (!empty($_js['code'])) {
        if (is_string($_js['code'])) {
            $_js['code'] = [$_js['code']];
        }
        foreach ($_js['code'] as $code) {
            if (is_string($code)) {
                $js .= trim(minify_js($code));
            }
        }
    }

    if (!empty($_js['_on'])) {
        if (is_string($_js['_on'])) {
            $_js['_on'] = ["click" => $_js['_on']];
        }
        foreach ($_js['_on'] as $e => $callback) {
            $callback = str_replace("</script>", "", str_replace("<script>", "", $callback));
            $js .= "<script>
                    $(function(){ $('#$args[id]').on('$e',function(){" . trim(minify_js($callback)) . "});});
                </script>";
        }
    }

    return $js;
}

function __html_menu($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'card kek_menu transition');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    $html = empty($args['title']) ? "" : "<h2 class='bold text_center padding_xsm_bottom'>$args[title]</h2>";
    foreach ($args['buttons'] as $display_name => $prop) {
        $id = empty($prop['id']) ? '' : "id='$prop[id]'";
        $class = empty($prop['class']) ? '' : "class='$prop[class]'";
        $href = empty($prop['href']) ? '' : "href='$prop[href]'";
        $icon = empty($prop['icon']) ? '' : "<i class='material-icons white'>$prop[icon]</i>";
        $target = empty($prop['target']) ? '' : "target='$prop[target]'";
        $element = "$icon<h3 class=''>$display_name</h3><h4>$prop[text]</h4>";
        $html .= "<a $id $class $href $target title='$display_name'>$element</a>";
    }

    return [
        'html'   => $html,
        'before' => $args['before'],
        'after'  => $args['after'],
        'prop'   => $args['prop'],
        'type'   => "div",
    ];
}

function __html_p($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', '');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "p",
    ];
}

function __html_script($args = [])
{
    return [
        'html' => $args['text'] ?? "",
        'prop' => $args['prop'],
        'type' => "script",
    ];
}

function __html_search($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'kek_search');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    $args['prop']['action'] = empty($args['action']) ? "" : $args['action'];

    $default_action = defined('CURRENT_PATH') ? CURRENT_PATH : null;
    $args['prop']['action'] = empty($args['prop']['action']) ? $default_action : $args['prop']['action'];

    $args['prop']['method'] = empty($args['method']) ? "" : $args['method'];
    $args['prop']['method'] = empty($args['prop']['method']) ? 'get' : $args['prop']['method'];

    if (empty($args['autofocus'])) {
        $args['autofocus'] = false;
    }
    $args['autofocus'] = $args['autofocus'] ? " autofocus" : "";
    if (empty($_REQUEST['q'])) {
        $_REQUEST['q'] = '';
    }

    $args['text'] = "<input id='q' name='q' value=\"$_REQUEST[q]\" placeholder='Search'$args[autofocus]>";

    if (!empty($_REQUEST['id'])) {
        $args['text'] = "<input type='hidden' name='id' value='" . $_REQUEST['id'] . "'>" . $args['text'];
    }

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => 'form',
    ];
}

function __html_shop_item($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'card shop_item');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];
    $args['prop']['id'] = empty($args['prop']['id']) ? '' : $args['prop']['id'];

    $prop = $args['prop'];
    $prop['price'] = empty($args['acl']) ? $prop['price'] : $prop["price_$args[acl]"];
    $prop['price'] = empty($prop['price']) ? $args['prop']['price'] : $prop["price"];

    $dealer = get_dealer();
    if (!empty($dealer)) {
        $level_discount = __config("SHOP_DISCOUNT_dealers_$dealer[account_level]");
        if (!empty($level_discount)) {
            $prop['price'] *= (float)$level_discount;
        }
    }

    $options = empty($args['options']) ? '' : generate_options($args['options']);

    $args['text'] = "<a href='$prop[image]' data-featherlight='image'><img class='shop_item_thumbnail' src='$prop[image]'></a>
    <h3 class='shop_item_title'>$prop[display_name]</h3><br>
    <p class='shop_item_price bold'>$" . "$prop[price]</p>
    $options
    <button class='shop_item_button'>Add to Cart</button><br>
    <p class='shop_item_bio'>$prop[bio]</p>";

    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "div",
    ];
}

function __html_style($args = [])
{
    return [
        'html' => $args['text'],
        'prop' => $args['prop'],
        'type' => "style",
    ];
}

function __html_table($args = [])
{
    $args['class_default'] = __html_element_default($args['type'], 'class', 'kek_table');
    $args['class'] = empty($args['class']) ? $args['class_default'] : $args['class_default'] . " $args[class]";
    $args['prop']['class'] = empty($args['prop']['class']) ? $args['class'] : $args['prop']['class'];

    $args['title'] = empty($args['title']) ? "" : $args['title'];
    $header = empty($args['title']) ? '' : "<h2 class='table_header text_center bold'>$args[title]</h2>";
    $args['before'] = "<div class='card table'>$header";
    $args['after'] = "</div>";

    $table_headers = '';
    foreach ($args['headers'] as $display_name => $class) {
        $table_headers .= "<th class='$class'>$display_name</th>";
    }

    $table_body = '';
    foreach ($args['elements'] as $element) {
        $obj = $element;
        if (is_object($element)) {
            $element = (array)$element;
        }

        $row_body = '';
        foreach ($args['fields'] as $field => $prop) {
            if ($field === 'button') {
                foreach ($prop['tokens'] as $search => $replace) {
                    $prop['href'] = str_replace($search, $element[$replace], $prop['href']);
                }

                $row_body .= "<td class='$prop[class]'><a href='$prop[href]'><img title='View' src='/img/icons/arrow_forward.svg'></a></td>";
                continue;
            }

            if (!empty($prop['type'])) {
                if ($prop['type'] === 'link') {
                    $href = empty($prop['href']) ? $element[$field] : $prop['href'];
                    if (!empty($prop['tokens'])) {
                        foreach ($prop['tokens'] as $search => $replace) {
                            $href = str_replace($search, $element[$replace], $href);
                        }
                    }

                    $target = empty($prop['target']) ? "" : " target='$prop[target]'";
                    $row_body .= "<td class='$prop[class]'><a href='$href'" . $target . ">$element[$field]</a></td>";
                    continue;
                }
            }

            if ($field === '__edit_button') {
                if (!is_object($obj)) {
                    echo __log('Table __edit_button Element is not an object...', $element);
                }

                $prop['button_class'] = empty($prop['button_class']) ? 'width_100 padding_0_x' : $prop['button_class'];
                $prop['field'] = empty($prop['field']) ? '__edit_' . $obj->id : $prop['field'];
                $prop['val'] = empty($prop['val']) && $prop['val'] !== '0' ? $obj->$prop['field'] : $prop['val'];

                $args_button = [
                    'class'   => $prop['button_class'],
                    'field'   => $prop['field'],
                    'icon'    => $prop['icon'],
                    'obj'     => $obj,
                    'run'     => $prop['run'],
                    'subtext' => $prop['subtext'],
                    'title'   => $prop['title'],
                    'var'     => $prop['var'],
                    'val'     => $prop['val'],
                ];

                $row_body .= "<td class='$prop[class]'>" . __html('edit_button', $args_button) . "</td>";
                continue;
            }

            if (is_string($prop)) {
                $prop = ['class' => $prop];
            }
            if (empty($element[$field])) {
                $element[$field] = 0;
            }
            $row_class = empty($prop['class']) ? '' : " class='$prop[class]'";
            $row_body .= "<td" . $row_class . ">$element[$field]</td>";
        }

        $table_body .= "<tr id='$element[id]' class='$element[id] text_center'>$row_body</tr>";
    }

    $args['html'] = "<thead><tr>$table_headers</tr></thead><tbody>$table_body</tbody>";

    return [
        'html'   => $args['html'],
        'before' => $args['before'],
        'after'  => $args['after'],
        'prop'   => $args['prop'],
        'type'   => $args['type'],
    ];
}
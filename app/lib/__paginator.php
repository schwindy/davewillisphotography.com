<?php
/**
 * @param array $args
 *  $args['count']:number - Number of elements to be paginated
 *  $args['max_page_buttons']:number - Maximum number of buttons shown in the Paginator HTML Element
 *  $args['p']:number - Current page index (where 0 is the first page)
 *  $args['page_path']:string - Path where the Paginator HTML Element should link to
 *  $args['ps']:number - Number of elements shown per page
 *  $args['q']:string (optional) - Search query used to generate elements
 * @return string - Paginator HTML element
 */
function create_paginator($args)
{
    $args['p'] = empty($args['p']) ? 0 : (int)$args['p'];
    $args['max_page_buttons'] = empty($args['max_page_buttons']) ? 5 : (int)$args['max_page_buttons'];
    $total_pages = ceil((float)$args['count'] / (float)$args['ps']);
    if (!$total_pages || ($total_pages == 1 && $args['p'] == 0)) {
        return false;
    }

    $html = '';
    $class = "page_select";
    $active_class = "page_select_active";
    $query = empty($args['q']) ? '' : "?q=$args[q]";
    $href = substr($args['page_path'], 0, 1) === '/' ? "$args[page_path]" . $query : "/$args[page_path]" . $query;
    $href_symbol = strpos($href, "?") === false ? '?' : '&';
    $engine_args = empty($args['date_start']) ? '' : "&date_start=$args[date_start]&date_end=$args[date_end]";

    if (!$args['p']) {
        for ($i = 0; $i < $args['max_page_buttons']; $i++) {
            if ($i >= $total_pages - 1) {
                break;
            }

            $page_index = $i + 1;
            $class_str = $i === $args['p'] ? "$class $active_class" : $class;
            $link = $href . $href_symbol . "p=$i" . $engine_args;
            $html = $html . "<a class='$class_str' href='$link'><p>$page_index</p></a>";
        }

        $total_index = $total_pages - 1;
        $link = $href . $href_symbol . "p=$total_index" . $engine_args;
        $html = $html . "<a class='$class' href='$link'><p>$total_pages</p></a>";
    } else {
        $link = $href . $href_symbol . "p=0" . $engine_args;
        $html = $html . "<a class='$class' href='$link'><p>1</p></a>";
        $after_html = "";
        $before_html = "";
        $count = 0;

        for ($i = $args['p']; $count < $args['max_page_buttons']; $i++) {
            if ($args['p'] >= $total_pages) {
                break;
            }
            if ($i >= $total_pages) {
                break;
            }
            if ($count >= $args['max_page_buttons'] / 2) {
                break;
            }

            $page_index = $i + 1;
            $class_str = $i === $args['p'] ? "$class $active_class" : $class;
            $link = $href . $href_symbol . "p=$i" . $engine_args;
            $after_html .= "<a class='$class_str' href='$link'><p>$page_index</p></a>";
            $count++;
        }

        if ($count < $args['max_page_buttons']) {
            for ($i = $args['p']; $count < $args['max_page_buttons']; $i--) {
                if ($i === $args['p']) {
                    continue;
                }
                if ($i === 0) {
                    break;
                }
                if ($i + 1 === $total_pages) {
                    break;
                }

                $page_index = $i + 1;
                $class_str = $i === $args['p'] ? "$class $active_class" : $class;
                $link = $href . $href_symbol . "p=$i" . $engine_args;
                $before_html = "<a class='$class_str' href='$link'><p>$page_index</p></a>" . $before_html;
                $count++;
            }
        }

        $html .= $before_html;
        $html .= $after_html;
    }

    $total_index = $total_pages - 1;
    $link = $href . $href_symbol . "p=$total_index" . $engine_args;
    $last_page_str = "<a class='$class' href='$link'><p>$total_pages</p></a>";
    $last_page_str_active = "<a class='$class $active_class' href='$link'><p>$total_pages</p></a>";
    if (strpos($html, $last_page_str) === false && strpos($html, $last_page_str_active) === false) {
        $html .= $last_page_str;
    }

    $prev_index = $args['p'] > 0 ? $args['p'] - 1 : "' style='display:none'";
    $next_index = $args['p'] + 1 < $total_pages ? $args['p'] + 1 : "' style='display:none'";

    $href_symbol = strpos($href, "?") === false ? '?' : '&';
    $href_prev = $href . $href_symbol . "p=$prev_index" . $engine_args;
    $href_next = $href . $href_symbol . "p=$next_index" . $engine_args;

    return "<div class='paginator_wrapper print_invisible'>
        <div class='page_navigation'>
            <a class='prev theme_color' href='$href_prev'>Previous</a>
            <a class='next theme_color' href='$href_next'>Next</a>
        </div>
        <div class='page_selection'>$html</div>
    </div>";
}
<?php
/** Search/Paginate a MySQL Table
 * @important: All search columns ($args['fields']) are required to be FULLTEXT Indexes. Alter Table Schema if required.
 *
 * @param: array $args - Arguments
 * @required:
 *      * table_name:string - The name of the MySQL Table we should search
 *
 * @optional:
 *      * class_name:string - The name of the Class that represents $args['table_name'] (defaults to "BaseClass")
 *      * date_start:date - Date to begin timeframe filter with (only required for "timeframe" engine)
 *      * date_end:date - Date to end timeframe filter with (only required for "timeframe" engine)
 *      * engine:string - Name of Search Engine (defaults to search by "query")
 *      * page_path:string - The name of the page that the paginator will link to (defaults to "search")
 *      * where:string - An optional WHERE clause that will be applied to all search queries
 *      * order_by:string - An optional WHERE clause that will be applied to all search queries
 *      * fields:array - Array that defines the columns to search & set relevancy multiplier (@see $args['fields] below)
 *
 * $args['fields]:
 * As shown below in the example, $args['fields'] is an array of key-value pairs where each key is the name of a table
 * column in MySQL that should be matched against the search query so that relevancy can be calculated and the search
 * results can be ranked and ordered.
 *
 * @note: All table columns you wish to include in a __search() MUST BE A FULLTEXT INDEX. You may need to add FULLTEXT
 * INDEX to your MySQL Table. An error message describing this scenario will be output in this scenario.
 *
 * Each value represents the "multiplier", or "significance" of the field in relativity to
 * the other fields. Multiplier values can be negative or positive integers, where a negative value represents
 * that the presence of the search query in that field will cause that result to drop by ($relevancy * $multiplier) and
 * a positive integer represents that the presence of the search query in that field will cause that result to increase
 * in ranking by ($relevancy * $multiplier).
 *
 * In other words, the higher the value, the more significant the field is. In the example below, the "fields" key in
 * $args has 4 fields: display_name, display_id, alt_name and bio. display_name has the highest multiplier, 3, which
 * means that display_name is seen as the most significant field. bio has the lowest multiplier, 1, which means that
 * is a minimally significant field but should still be considered when ordering results.
 *
 * $_REQUEST variables:
 * 1.) $_REQUEST['q'] | The search query (string)
 * 2.) $_REQUEST['p'] | The pagination current page, where the first page is zero
 * 3.) $_REQUEST['ps'] | The pagination page size, aka maximum elements per page
 *
 * @note: All $_REQUEST variables can be overridden by passing a conflicting key, such as $args['q'], to __search($args)
 * @note: Most $args can be assigned using $_REQUEST variables but all $_REQUEST values are overridden by $args. This is
 * used to set defaults for fields that you do not want to pass each execution.
 *
 * @example:
 * ---------------------------------------------------------------------------------------------------------------------
 * $search = __search
 * (
 * [
 * "table_name"=>"tickets",
 * "class_name"=>"Ticket",
 * "page_path"=>CURRENT_PATH,
 * "where"=>"NOT status='deleted'",
 * "order_by"=>"id DESC",
 * "fields"=>
 * [
 * "customer_email"=>4,
 * "customer_name"=>3,
 * "subject"=>2,
 * "status"=>2,
 * "message"=>1,
 * ],
 * ]
 * );
 *
 * echo __html
 * (
 * 'card',
 * [
 * 'text'=>
 * __html('h1', ['text'=>'Search']).
 * __html('search')
 * ]
 * );
 *
 * echo __html
 * (
 * 'table',
 * [
 * 'elements'=>$search['elements'],
 * 'headers'=>
 * [
 * 'Customer Name'=>'width_10',
 * 'Subject'=>'width_10',
 * 'Last Message'=>'width_10',
 * 'Manage'=>'width_5 print_invisible',
 * ],
 * 'fields'=>
 * [
 * 'customer_name'=>'',
 * 'subject'=>'',
 * 'last_reply'=>'',
 * 'button'=>
 * [
 * 'class'=>'button blue_bg white print_invisible',
 * 'href'=>'/admin/support/ticket?id=$id',
 * 'tokens'=>['$id'=>'id']
 * ]
 * ],
 * ]
 * );
 *
 * echo $search['paginator_html'];
 *
 * @return array $search_result - Search Result Object (@see below)
 * Search Result Object
 * [
 *      'paginator_html'=> Paginator HTML element (string),
 *      'elements'=> Array of $args['class_name'] Objects found by __search(),
 *      'html'=> "No results found" HTML Element (string)
 * ]
 */
function __search($args)
{
    $db = Database::getInstance();
    $args = array_merge($_REQUEST, $args);
    if (empty($args['table_name'])) {
        return false;
    }

    $args['p'] = empty($args['p']) ? 0 : (int)$args['p'];
    $args['ps'] = empty($args['ps']) ? 40 : (int)$args['ps'];
    $args['s'] = $args['p'] * $args['ps'];

    // Clean Query
    $args['q'] = $db->clean($args['q']);
    $args['q'] = strpos($args['q'], '\'') === false ? $args['q'] : str_replace('\'', '\\\'', $args['q']);

    if (!empty($_REQUEST['ps'])) {
        $args['ps'] = $_REQUEST['ps'];
    }

    $args['class_name'] = empty($args['class_name']) ? "BaseClass" : $args['class_name'];
    $args['match_query'] = "";
    $args['order_relevance'] = "";
    $args['order_by'] = empty($args['order_by']) ? "" : "ORDER BY $args[order_by]";
    $args['page_path'] = empty($args['page_path']) ? CURRENT_PATH : $args['page_path'];
    $args['select'] = "*";
    $args['where'] = empty($args['where']) ? "(1=1)" : $args['where'];
    $args['where_search'] = empty($args['fields']) ? "(1=1)" : "(";

    // Generate match_query/order_relevance/where_search clauses
    foreach ($args['fields'] as $field => $multiplier) {
        if (empty($multiplier)) {
            $multiplier = 1;
        }
        $args['match_query'] .= "MATCH($field) AGAINST('$args[q]') as relevance_$field, ";
        $args['order_relevance'] .= "(relevance_$field * $multiplier) + ";
        $args['where_search'] .= "INSTR($field, '$args[q]') > 0 OR ";
    }

    // Clean match_query/order_relevance/where_search clauses
    $args['match_query'] = str_lreplace(",", "", $args['match_query']);
    $args['order_relevance'] = str_lreplace("+", "DESC", $args['order_relevance']);
    $args['where_search'] = str_lreplace("OR", ")", $args['where_search']);

    if (!empty($args['engine'])) {
        // Run Search using desired Engine
        if ($args['engine'] === 'query') {
            $args['count'] = $db->count($args['table_name'], "$args[where] AND $args[where_search]");
            $args['order_by'] = empty($args['order_by']) ? "ORDER BY $args[order_relevance]" : $args['order_by'];
            $args['select'] = "*, $args[match_query]";
            $args['where'] = "$args[where] AND $args[where_search]";
        } else {
            if ($args['engine'] === 'timeframe') {
                $args['date_start'] = get_date($args['date_start'], 'Y-m-d H:i:s');
                $args['date_end'] = get_date($args['date_end'], 'Y-m-d H:i:s');
                $args['where'] = "created >= '$args[date_start]' AND created <= '$args[date_end]'";
                $args['count'] = $db->count($args['table_name'], $args['where']);
            }
        }
    } else {
        if (empty($args['q'])) {
            // Run Default Search
            $args['count'] = $db->count($args['table_name'], $args['where']);
            $args['where'] = "$args[where] AND $args[where_search]";
        } else {
            // Run Primary Search
            $args['count'] = $db->count($args['table_name'], "$args[where] AND $args[where_search]");
            $args['order_by'] = empty($args['order_by']) ? "ORDER BY $args[order_relevance]" : $args['order_by'];
            $args['select'] = "*, $args[match_query]";
            $args['where'] = "$args[where] AND $args[where_search]";
        }
    }

    $limit = "LIMIT $args[s],$args[ps]";
    $query = "SELECT $args[select] FROM $args[table_name] WHERE $args[where] $args[order_by] $limit";
    $elements = $db->get_rows($query);

    $elements_final = [];
    foreach ($elements as $element) {
        $elements_final[] = new $args['class_name']($element);
    }

    $search_result = [
        'elements'       => $elements_final,
        'html'           => '',
        'paginator_html' => create_paginator($args),
    ];

    if (empty($elements_final)) {
        if ($args['p']) {
            redirect_to($args['page_path']);
        }
        $search_result['html'] = "<p class='text_center'>No results found...</p><p class='text_center'>):</p>";
    }

    return $search_result;
}
<?php
$search = __search([
    "table_name" => "docs",
    "class_name" => "Doc",
    "ps" => 1000,
    "order_by" => "created DESC",
    "where" => "is_public='true'",
    "fields" => [
        "display_name" => 5,
        "bio" => 4,
        "tags" => 3,
    ],
]);

echo __html('card', [
    'class' => 'text_center',
    'text'  => __html('h1', COMPANY_NAME . " Gallery") . __html('search')
]);

echo empty($search['elements']) ? $search['html'] : generate_gallery($search['elements']), $search['paginator_html'];
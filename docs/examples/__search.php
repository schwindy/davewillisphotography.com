<?php
// @SEE: ./app/lib/__search.php
// @IMPORTANT: All __search $args['fields'] must be FULLTEXT Indexes in MySQL. You may need to alter your Table Schema.
$search = __search([
    "table_name" => "parts",
    "class_name" => "Part",
    "page_path"  => CURRENT_PATH,
    "ps"         => "500",
    "order_by"   => "part_group ASC, part_id ASC",
    "fields"     => [
        "part_num"   => 3,
        "part_name"  => 3,
        "part_group" => 2,
        "bio"        => 2,
        "components" => 2,
        "type"       => 1,
    ],
]);

echo empty($search['elements']) ? $search['html'] : __html('table', [
    'elements' => $search['elements'],
    'headers'  => [
        'Customer Name' => 'width_10',
        'Subject'       => 'width_10',
        'Status'        => 'width_5',
        'Last Message'  => 'width_5',
        'Manage'        => 'width_5 print_invisible',
    ],
    'fields'   => [
        'customer_name' => '',
        'subject'       => '',
        'status'        => '',
        'last_reply'    => '',
        'button'        => [
            'class'  => 'button blue_bg white print_invisible',
            'href'   => '/admin/support/ticket?id=$id',
            'tokens' => ['$id' => 'id']
        ]
    ],
]), $search['paginator_html'];
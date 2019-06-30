<?php
acl_require_admin(CURRENT_PATH);

$search = __search
(
    [
        "table_name"=>"docs",
        "class_name"=>"Doc",
        "order_by"=>"display_name ASC",
        "fields"=>
        [
            "display_name"=>5,
            "bio"=>4,
            "tags"=>3,
            "file_type"=>2,
            "file_ext"=>1,
        ],
    ]
);

echo __html
(
    'menu',
    [
        'title'=>'Docs Menu',
        'buttons'=>
        [
            'Create Document'=>
            [
                'href'=>'/admin/docs/create',
                'text'=>'Create a New Support Document',
            ],
        ],
    ]
);

echo __html
(
    'card',
    [
        'class' => 'text_center',
        'text' => __html('h1', "Search") . __html('search')
    ]
);

echo empty($search['elements'])?$search['html']:__html
(
    'table',
    [
        'title'=>'Documents',
        'elements'=>$search['elements'],
        'headers'=>
        [
            'Display Name'=>'',
            'Is Public'=>'',
            'File Type'=>'',
            'Created'=>'',
            'Manage'=>'print_invisible',
        ],
        'fields'=>
        [
            'display_name'=> ['class'=>'bold'],
            'is_public'=>[],
            'file_type'=>[],
            'created'=>[],
            'button'=>
            [
                'class'=>'button blue_bg white print_invisible',
                'href'=>'/admin/support/docs/view?id=$id',
                'tokens'=>['$id'=>'id']
            ]
        ],
    ]
), $search['paginator_html'];
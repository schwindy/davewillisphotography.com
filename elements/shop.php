<?php

$search = __search
(
    [
        "table_name"=>"elements",
        "class_name"=>"Element",
        "page_path"=>"/shop",
        "order_by"=>"display_name ASC",
        "where"=>"type='shop_item'",
        "fields"=>
        [
            "display_name"=>3,
            "data"=>3,
            "properties"=>3,
            "notes"=>10,
            "type"=>1,
        ],
    ]
);

echo __html
(
    'card',
    [
        'class'=>'text_center',
        'text'=>
            __html('h1', ['text'=>'Shop']).
            __html('p', ['text'=>'Welcome to the '.SITE_NAME.' Shop!']).
            __html('search').
            "<a class='text_center white purple_bg padding_sm width_70 margin_auto_x display_block' href='/cart'>View Cart</a>"
    ]
);

//echo __html
//(
//    'menu',
//    [
//        'title'=>'Shop Categories',
//        'buttons'=>
//        [
//            'Full Systems'=>
//            [
//                'href'=>'/shop?q=PWR-ARM-Systems',
//            ],
//            'Accessories'=>
//            [
//                'href'=>'/shop?q=PWR-ARM-Accessories',
//            ],
//            'Canvas'=>
//            [
//                'href'=>'/shop?q=PWR-ARM-Canvas',
//            ],
//            'Parts'=>
//            [
//                'href'=>'/shop?q=PWR-ARM-Parts',
//            ],
//        ],
//    ]
//);

echo generate_shop($search['elements']);
echo $search['paginator_html'];
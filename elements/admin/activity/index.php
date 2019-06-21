<?php
echo Activity::search([
    "table_name" => "activity",
    "class_name" => "Activity",
    "href"       => '/admin/activity/view?id=$id',
    "ps"         => 100,
    "order_by"   => "updated DESC",
    "fields"     => [
        "user_id" => 5,
        "path"    => 3,
        "ip"      => 10,
        "action"  => 2,
    ],
]);
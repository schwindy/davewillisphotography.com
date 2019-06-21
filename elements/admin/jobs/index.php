<?php
echo Job::search([
    "table_name" => "jobs",
    "class_name" => "Job",
    "ps"         => 100,
    "page_path"  => CURRENT_PATH . "?id=$obj->id",
    "order_by"   => "updated DESC",
    "fields"     => [
        "run"    => 5,
        "status" => 5,
    ],
]);
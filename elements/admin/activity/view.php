<?php
$row = $db->get_row("SELECT * FROM activity WHERE id='$_REQUEST[id]'");
if (empty($row)) {
    redirect_to($route->home);
}
$obj = new Activity($row);

echo Activity::search([
    "table_name" => "activity",
    "class_name" => "Activity",
    "href"       => '/admin/activity/view?id=$id',
    "ps"         => 25,
    "page_path"  => CURRENT_PATH . "?id=$obj->id",
    "where"      => "(NOT user_id='anon' AND user_id='$obj->user_id') OR ip='$obj->ip'",
    "order_by"   => "updated DESC",
    "fields"     => [
        "path"    => 5,
        "action"  => 5,
        "ip"      => 5,
        "user_id" => 10,
    ],
]);
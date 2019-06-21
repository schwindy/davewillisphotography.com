<?php
// @SEE: ./app/lib/__html.php
echo __html('edit_button', [
    'field'         => "part_name",
    'icon'          => 'leave_empty_to_use: keyboard_arrow_right',
    'input_default' => 'leave_empty_to_use: $obj->$field',
    'obj'           => $obj,
    'run'           => "admin/inventory/parts/update_part_name",
    'subtext'       => "Enter a new Part Name",
    'title'         => "Part Name",
    'var'           => "Name",
]);
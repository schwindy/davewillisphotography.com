<?php

/** Generate a Part Components HTML Element
 * @param Part $part
 * @return string $html
 */
function generate_components_html($part)
{
    $db = Database::getInstance();

    $args = [];
    $components_html = "";

    if (empty($part)) {
        $args['run'] = 'admin/inventory/parts/create';
    } else {
        if (get_class($part) === 'Part') {
            foreach ($part->components as $key => $val) {
                $row = $db->get_row("SELECT * FROM parts WHERE part_num='$val[part_num]'");
                if (empty($row)) {
                    continue;
                }

                $components_html .= "<div class='component'>
                <input class='var display_none' value='$row[part_num]' disabled>
                <input class='text_right' value='$row[part_name]' disabled>
                <input class='val' value='$val[amount]'>
                <button class='remove padding_xxsm_y red_bg white bold'>Remove</button>
            </div>";
            }
        }
    }

    $html = __html('card', [
        'class' => '',
        'text'  => __html('h1',
                ['text' => "Components", 'prop' => ['class' => 'text_left']]) . __html('br') . __html('button', [
                'id'    => 'add_component',
                'color' => 'purple',
                'icon'  => 'add',
                'text'  => 'Add Component',
            ]) . __html('button', [
                'id'    => 'kek_submit',
                'color' => 'blue',
                'icon'  => 'input',
                'text'  => 'Save',
            ]) . __html('br') . "<div class='components'>$components_html</div>"
    ]);

    $html .= "<script>components = new Components('" . json_encode($args) . "');</script>";

    return $html;
}
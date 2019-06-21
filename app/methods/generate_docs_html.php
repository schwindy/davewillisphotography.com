<?php

/** Generate a Docs HTML Element
 * @param Doc $template
 * @param Array $args
 * @return string $html
 */
function generate_docs_html($template, $args = [])
{
    $db = Database::getInstance();

    $args_js = [];
    $docs_html = "";

    if (get_class($template) === 'Template') {
        foreach ($template->docs as $id) {
            $row = $db->get_row("SELECT * FROM docs WHERE id='$id'");
            if (empty($row)) {
                continue;
            }

            $docs_html .= "<div class='doc'>
                <input class='var val display_none' value='$row[id]' disabled>
                <input class='text_right' value='$row[display_name]' disabled>
                <button class='remove padding_xxsm_y red_bg white bold'>Remove</button>
            </div>";
        }
    }

    if (!empty($args['no_card'])) {
        $html = __html('h1', ['text' => "Docs", 'prop' => ['class' => 'text_left']]) . __html('br') . __html('button', [
                'id'    => 'add_doc',
                'color' => 'purple',
                'icon'  => 'add',
                'text'  => 'Add Doc',
            ]) . __html('button', [
                'id'    => 'kek_submit',
                'color' => 'blue',
                'icon'  => 'input',
                'text'  => 'Save',
            ]) . __html('br') . "<div class='docs'>$docs_html</div>";
    } else {
        if (!empty($args['minimal'])) {
            $html =
                __html('h1', ['text' => "Docs", 'prop' => ['class' => 'text_left']]) . __html('br') . __html('button', [
                    'id'    => 'add_doc',
                    'color' => 'purple',
                    'icon'  => 'add',
                    'text'  => 'Add Doc',
                ]) . __html('br') . "<div class='docs'>$docs_html</div>";
        } else {
            $html = __html('card', [
                'class' => '',
                'text'  => __html('h1',
                        ['text' => "Docs", 'prop' => ['class' => 'text_left']]) . __html('br') . __html('button', [
                        'id'    => 'add_doc',
                        'color' => 'purple',
                        'icon'  => 'add',
                        'text'  => 'Add Doc',
                    ]) . __html('button', [
                        'id'    => 'kek_submit',
                        'color' => 'blue',
                        'icon'  => 'input',
                        'text'  => 'Save',
                    ]) . __html('br') . "<div class='docs'>$docs_html</div>"
            ]);
        }
    }

    $html .= "<script>docs = new Docs('" . json_encode($args_js) . "');</script>";

    return $html;
}
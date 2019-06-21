<?php

function generate_templates_table($tickets = [], $table_header = 'Templates')
{
    $db = Database::getInstance();
    $table_body = '';

    if (empty($tickets)) {
        $tickets = $db->get_rows("SELECT * FROM templates WHERE type='open' ORDER BY display_name ASC");
    }

    foreach ($tickets as $ticket) {
        if (is_object($ticket)) {
            $ticket = (array)$ticket;
        }
        $id = $ticket['id'];
        $table_body .= "<tr class='text_center'>
            <td>" . $ticket['display_name'] . "</td>
            <td>" . substr($ticket['notes'], 0, 30) . "</td>
            <td>" . $ticket['updated'] . "</td>
            <td class='button blue_bg white'>
                <a href='/admin/support/template_reply?id=$id'>
                    <img src='/img/icons/input_icon.png'>
                </a>
            </td>
        </tr>";
    }

    return "
    <div class='card table'>
        <h2 class='table_header text_center bold'>$table_header</h2>
        <table class='kek_table' table_type='active_tickets_table'>
            <thead>
                <tr>
                    <th class='button width_5'>Name</th>
                    <th class='button width_5'>Notes</th>
                    <th class='button width_5'>Last Update</th>
                    <th class='button width_5'>Manage</th>
                </tr>
            </thead>
            <tbody>
                $table_body
            </tbody>
        </table>
    </div>
    ";
}
<?php

function create_reply()
{
    if (empty($_REQUEST['id'])) {
        new APIResponse(0, "Missing id");
    }
    if (empty($_REQUEST['user_id'])) {
        new APIResponse(0, "Missing user_id");
    }
    if (empty($_REQUEST['message'])) {
        new APIResponse(0, "Message is empty!");
    }

    $db = Database::getInstance();

    $_REQUEST['message'] = nl2br($_REQUEST['message']);

    $ticket = $db->get_row("SELECT * FROM tickets WHERE id='$_REQUEST[id]'");
    $docs = [];

    $template = new Template(['docs' => []]);
    if (!empty($_REQUEST['__template_id'])) {
        $row = $db->get_row("SELECT * FROM templates WHERE type='reply' AND id='$_REQUEST[__template_id]'");
        if (empty($row)) {
            new APIResponse(0, "Invalid template_id");
        }
        $template = new Template($row);
        $_REQUEST['message'] = $template->data;
    }

    if (!empty($_REQUEST['docs'])) {
        foreach ($_REQUEST['docs'] as $id) {
            $template->docs[$id] = $id;
        }
    }

    $docs = $template->docs_html();

    $db->insert("replies", [
        'id'        => generate_mysql_id(),
        'ticket_id' => $_REQUEST['id'],
        'user_id'   => $_REQUEST['user_id'],
        'message'   => $_REQUEST['message'],
        'docs'      => json_encode($template->docs),
        'created'   => get_date(),
    ]);

    $db->update('tickets', [
        'status'     => 'open',
        'last_reply' => get_date(),
    ], [
        'id' => $_REQUEST['id'],
    ]);

    if ($_REQUEST['user_id'] === $ticket['customer_email']) {
        // Email Staff
        $to = SUPPORT_EMAIL;
        $subject = COMPANY_NAME . " Support | Ticket #$ticket[display_id]";
        $message = "<html>
                <head>
                    <title>New Reply - Ticket #$ticket[display_id]</title>
                </head>
                <body>
                    <h1 style='color:#000;'>A Customer Replied To A Ticket!</h1>
                    <h3 style='color:#000;'>Visit the <a href='" . SITE_URL . "/admin/support/ticket?id=$ticket[id]'>Support Portal</a> to manage this Ticket.</h3>
                    <br>
                    <h2 style='color:#000'>Message</h2>
                    <p style='color:#000;font-size:12pt;'>$_REQUEST[message]</p>
                </body>
            </html>";
    } else {
        // Email Customer
        $to = $ticket['customer_email'];
        $subject = COMPANY_NAME . " Support | Ticket #$ticket[display_id]";
        $message = "<html>
                <head>
                    <title>New Reply</title>
                </head>
                <body>
                    <h1 style='color:#000;'>A Staff Member Responded To Your Ticket!</h1>
                    <h2 style='color:#000;'>To Reply, <a href='" . SITE_URL . "/contact?id=$ticket[id]'>Click Here</a>.</h2>
                    <br>
                    <h2 style='color:#000'>Message</h2>
                    <p style='color:#000;font-size:12pt;'>$_REQUEST[message]</p> 
                    <br>
                    $docs
                    <p style='color:#000;font-size:12pt;'>Feel free to call us at " . SUPPORT_PHONE . " if you need immediate assistance.</p>
                    <h2 style='color:#000;'>To Reply, <a href='" . SITE_URL . "/contact?id=$ticket[id]'>Click Here</a>.</h2>
                </body>
            </html>";
    }

    send_email($to, $subject, $message);
}
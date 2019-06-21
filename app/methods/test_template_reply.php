<?php

function test_template_reply()
{
    if (empty($_REQUEST['id'])) {
        return false;
    }

    if (empty($_REQUEST['email'])) {
        return false;
    }

    $db = Database::getInstance();

    $template = $db->get_row("SELECT * FROM templates WHERE id='$_REQUEST[id]'");
    $ticket = $db->get_row("SELECT * FROM tickets WHERE id='0'");

    // Email Staff
    $to = $_REQUEST['email'];
    $subject = COMPANY_NAME . " Support | Ticket #0000000 (test)";
    $message = "<html>
            <head>
                <title>New Reply - Ticket #$ticket[id] (test)</title>
            </head>
            <body>
                <h1 style='color:#000;'>A Staff Member has responded to your Ticket!</h1>
                <br>
                <h2 style='color:#000'>Message</h2>
                <p style='color:#000;font-size:12pt;'>$template[data]</p> 
                <h3 style='color:#000;'>To Reply, <a href='" . SITE_URL . "/admin/support/ticket?id=$ticket[id]&email=$to'>Click Here</a></h3>
                <br>
                <p style='color:#000;font-size:12pt;'>Feel free to call us at " . SUPPORT_PHONE . " if you need immediate assistance.</p>
            </body>
        </html>";

    return send_email($to, $subject, $message);
}
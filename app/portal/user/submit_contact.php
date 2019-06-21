<?php
function submit_contact()
{
    if (empty($_REQUEST['customer_name'])) {
        new APIResponse(0, "Missing customer_name");
    }
    if (empty($_REQUEST['customer_email'])) {
        new APIResponse(0, "Missing customer_email");
    }
    if (empty($_REQUEST['subject'])) {
        new APIResponse(0, "Missing subject");
    }
    if (empty($_REQUEST['message'])) {
        new APIResponse(0, "Missing message");
    }

    // Email Staff
    $to = SUPPORT_EMAIL;
    $subject = COMPANY_NAME . " Support | New Ticket";
    $message = "<html>
                    <head>
                        <title>New Ticket - $_REQUEST[subject]</title>
                    </head>
                    <body>
                        <h1 style='color:#000;'>New Support Ticket</h1>
                        <br>
                        <h2 style='color:#000'>Ticket Information</h2>
                        <p style='color:#000;font-size:12pt;'>Subject: $_REQUEST[subject]</p>
                        <p style='color:#000;font-size:12pt;'>Message: $_REQUEST[message]</p>
                        <p style='color:#000;font-size:12pt;'>Name: $_REQUEST[customer_name]</p>
                        <p style='color:#000;font-size:12pt;'>Email: $_REQUEST[customer_email]</p>
                        <p style='color:#000; font-size:12pt;'>Customer IP: $_SERVER[REMOTE_ADDR]</p>
                    </body>
                </html>";
    send_email($to, $subject, $message);

    // Email Customer
    $to = $_REQUEST['customer_email'];
    $subject = COMPANY_NAME . " Support | New Ticket";
    $message = "<html>
                    <head>
                        <title>New Customer Support Ticket</title>
                    </head>
                    <body>
                        <h1 style='color:#000;'>" . COMPANY_NAME . " Support</h1>
                        <h2 style='color:#000;'>A New Support Ticket has been created for you!</h2>
                        <p style='color:#000;font-size:12pt;'>A Staff Member will contact you shortly via email.</p>
                        <br>
                        <h3 style='color:#000;'>See below for a copy of your Ticket Information:</h3>
                        <p style='color:#000;font-size:12pt;'>Name: $_REQUEST[customer_name]</p>
                        <p style='color:#000;font-size:12pt;'>Email: $_REQUEST[customer_email]</p>
                        <p style='color:#000;font-size:12pt;'>Subject: $_REQUEST[subject]</p>
                        <p style='color:#000;font-size:12pt;'>Message: $_REQUEST[message]</p>
                    </body>
                </html>";
    send_email($to, $subject, $message);

    new APIResponse(1, "Your contact submission has been received! We will get back to you as soon as possible.");
}
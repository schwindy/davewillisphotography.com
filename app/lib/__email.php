<?php

function send_email($to, $subject, $message)
{
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;" . "\r\n";
    $headers .= 'From: ' . SUPPORT_EMAIL . "\r\n" . 'Reply-To: ' . SUPPORT_EMAIL . "\r\n" . 'X-Mailer: PHP/' . phpversion();

    return mail($to, $subject, $message, $headers);
}
<?php
/**
 * @author jmquijano
 */

require("src/EZPHPEmailer.php");

$config = [
    "host" => "", // SMTP Host
    "smtpuser" => "", // Your Google Address (e.g. user@gmail.com)
    "smtppass" => "", // Your Password
    "smtpport" => 587
];
$mail = new jmq\EZPHPEmailer\Emailer();

/**
 * Nested Method Calling
 */
$mail->config($config)
    ->to('', '')
    ->from('')
    ->attachment('')
    ->subject('')
    ->body('')
    ->send();




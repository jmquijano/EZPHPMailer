# EZPHPMailer
Using PHPMailer and class builder to make the sending process simpler.

# How to use
Download and extract the contents PHPEmailer.

```php
<?php
    require("src/EZPHPEmailer.php");
    
    $config = [
        "host" => "smtp.gmail.com", // SMTP Host
        "smtpuser" => "", // SMTP Username (e.g. user@email.com)
        "smtppass" => "", // SMTP Password
        "smtpport" => 587 // SMTP Host Port
    ];
    
    // Instantiate the object from class
    $emailer = new jmq\EZPHPEmailer\Emailer();
    
    $emailer->config($config)
    ->to('Sender Email', 'Sender Name')
    ->from('')
    ->attachment('')
    ->subject('')
    ->body('')
    ->send();
?>
```

# Methods
| Name | Description | Parameter(s) | Usage
| --- | --- | --- | ---
| config($configParams) | Set configuration array parameter. | (array) $configParams | Must be called once right after the class was instantiated.
| to($address, $name) | Recipient email address | $address must be a valid email address string, $name is iptional. | This can be called a multiple times depending on how many will be the recipients.
| from($address, $name) | Sender email address | $address must be a valid email address string, $name is iptional. | This can be called once to set the sender (from).
| cc($address, $name) | CC email address | $address must be a valid email address string, $name is iptional. | This can be called a multiple times depending on how many will be the CC recipients.
| bcc($address, $name) | BCC email address | $address must be a valid email address string, $name is iptional. | This can be called a multiple times depending on how many will be the CC recipients.
| attachment($filepath, $filename) | To attach file(s) | $filepath must be a valid file, $filename is optional | This can be called a multiple times to add multiple files.
| body($content, $ishtml, $altcontent) | Email message body. Format can be plain text or HTML. | $content must be set and could be in a string or HTML format. $ishtml must be boolean and its default value is false. $altcontent is the alternate content body for older browser in plain text format. | This should be called once, message body content must be set once.
| send() | To initiate a send email action. | Not applicable | This should be called once to initiate send mail function.

> You can contact me through my Github (@jmquijano) or email.

> Email Address: justinelouis.quijano@ama.edu.ph

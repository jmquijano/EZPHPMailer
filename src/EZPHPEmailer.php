<?php
/**
 * @author jmquijano
 * EZPHPEmailer utilizes PHPMailer and is developed to do magic method calling.
 */

namespace jmq\EZPHPEmailer;

require("vendor/phpmailer/Exception.php");
require("vendor/phpmailer/PHPMailer.php");
require("vendor/phpmailer/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Emailer
{
    private $mailer;
	
    protected $fileAttachmentCollections;
    protected $RecipientCollections;
    protected $CCRecipientCollections;
    protected $BCCRecipientCollections;

    /**
     * @param $filepath
     * @param string $filename
     * @return $this
     * @throws \Exception
     *
     */
    private function AddFileAttachmentCollection($filepath, $filename = '') {
        if (isset($filepath)) {
            if (!empty($filepath)) {
                if (file_exists($filepath)) {
                    $this->fileAttachmentCollections[] = ["filepath" => $filepath, "filename" => $filename];
                } else {
                    throw new \Exception("File Attachment Error: File was not found.");
                }

            } else {
                throw new \Exception("File Attachment Error: File path should not be null");
            }

        } else {
            throw new \Exception("File Attachment Error: File Path must be set correctly");
        }
        return $this;
    }

    /**
     * @param $address
     * @param string $name
     * @throws \Exception
     */
    private function AddRecipientCollection($address, $name = '')
    {
        if (isset($address)) {
            if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
                $this->RecipientCollections[] = ["address" => $address, "name" => $name];
            } else {
                throw new \Exception("Recipient Error: Invalid email address");
            }
        } else {
            throw new \Exception("Recipient Error: Email address must be set correctly");
        }
    }

    /**
     * @param $address
     * @param string $name
     * @throws \Exception
     */
    private function AddCCRecipientCollection($address, $name = '')
    {
        if (isset($address)) {
            if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
                $this->CCRecipientCollections[] = ["address" => $address, "name" => $name];
            } else {
                throw new \Exception("Recipient Error (CC): Invalid email address");
            }
        } else {
            throw new \Exception("Recipient Error (CC): Email address must be set correctly");
        }
    }

    /**
     * @param $address
     * @param string $name
     * @throws \Exception
     */
    private function AddBCCRecipientCollection($address, $name = '')
    {
        if (isset($address)) {
            if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
                $this->BCCRecipientCollections[] = ["address" => $address, "name" => $name];
            } else {
                throw new \Exception("Recipient Error (BCC): Invalid email address");
            }
        } else {
            throw new \Exception("Recipient Error (BCC): Email address must be set correctly");
        }
    }

    /**
     * @param $address
     * @param string $name
     * @return $this
     * @throws \Exception
     */
    public function from($address, $name = '')
    {
        if (isset($address)) {
            if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
                $this->mailer->setFrom($address, $name);
            } else {
                throw new \Exception("Sender Error (From): Invalid email address");
            }
        } else {
            throw new \Exception("Sender Error (From): Email address must be set correctly");
        }

        return $this;
    }

    /**
     * @param $set
     * @return $this
     * @throws \Exception
     */
    public function subject($set)
    {
        if (isset($set)) {
            $this->mailer->Subject = $set;
        } else {
            throw new \Exception("Message Subject Error: Subject must be set correctly");
        }

        return $this;
    }

    /**
     * @param $set
     * @param bool $ishtml
     * @param string $altbody
     * @return $this
     * @throws \Exception
     */
    public function body($set, $ishtml = false, $altbody = '')
    {
        if (isset($set)) {
            $this->mailer->Body = $set;
            $this->mailer->AltBody = $altbody;
            $this->mailer->isHTML($ishtml);
        } else {
            throw new \Exception("Message Body Error: Body must be set correctly.");
        }

        return $this;
    }

    /**
     * @param string $configArray
     * @return $this
     * @throws \Exception
     */
    public function config($configArray = '')
    {
        if (is_array($configArray)) {
            if (isset($configArray["host"])
                && isset($configArray["smtpuser"])
                && isset($configArray["smtppass"])
                && isset($configArray["smtpport"])
            ) {
                $this->mailer = new PHPMailer(true);

                $this->mailer->SMTPDebug = 0;
                $this->mailer->isSMTP();
                $this->mailer->Host = $configArray["host"];
                $this->mailer->SMTPAuth = true;
                $this->mailer->Username = $configArray["smtpuser"];
                $this->mailer->Password = $configArray["smtppass"];
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $this->mailer->SMTPSecure = true;
                $this->mailer->SMTPAuth = true;
                $this->mailer->Port = $configArray["smtpport"];

                // Have to manually set language if PHPMailer can't determine
                $this->mailer->SetLanguage("en", 'includes/phpMailer/language/');

            } else {
                throw new \Exception("One or more of the required parameters on the configuration array were not set properly.");
            }
        } else {
            throw new \Exception("Configuration in array form must be set properly.");
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function checkcollections() {
        if (is_array($this->fileAttachmentCollections)) {
            foreach ($this->fileAttachmentCollections As $FileAttachmentCollection) {
                $this->mailer->addAttachment($FileAttachmentCollection["filepath"], $FileAttachmentCollection["filename"]);
            }
        }

        if (is_array($this->RecipientCollections)) {
            foreach ($this->RecipientCollections as $RecipientCollection) {
                $this->mailer->addAddress($RecipientCollection["address"], $RecipientCollection["name"]);
            }


        }

        if (is_array($this->CCRecipientCollections)) {
            foreach ($this->CCRecipientCollections as $CCRecipientCollection) {
                $this->mailer>addCC($CCRecipientCollection["address"], $CCRecipientCollection["name"]);
            }
        }

        if (is_array($this->BCCRecipientCollections)) {
            foreach ($this->BCCRecipientCollections as $BCCRecipientCollection) {
                $this->mailer>addCC($BCCRecipientCollection["address"], $BCCRecipientCollection["name"]);
            }
        }
        return $this;
    }

    /**
     * @return void
     */
    public function send() {
        $this->checkcollections();
        $this->mailer->send();
    }

    /**
     * @param $method
     * @param $args
     * @return $this
     */
    public function __call($method, $args)
    {
        $method = strtolower($method);

        switch ($method) {
            case "attachment":
                call_user_func_array(array($this,'AddFileAttachmentCollection'), $args);
                break;
            case "to":
                call_user_func_array(array($this,'AddRecipientCollection'), $args);
                break;
            case "cc":
                call_user_func_array(array($this,'AddCCRecipientCollection'), $args);
                break;
            case "bcc":
                call_user_func_array(array($this,'AddBCCRecipientCollection'), $args);
                break;
        }

        return $this;
    }
}


?>
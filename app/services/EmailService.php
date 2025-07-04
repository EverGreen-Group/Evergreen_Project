<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mailer;

    public function __construct()
    {

        $config = require_once __DIR__ . '/../config/email.php';

        $this->mailer = new PHPMailer(true);

        $this->mailer->isSMTP();
        $this->mailer->Host = $config['host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['username'];
        $this->mailer->Password = $config['password'];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = 587;

        // Set default sender
        $this->mailer->setFrom('simaakniyaz@gmail.com', 'Evergreen Tea Factory');
    }

    /**
     * Send an email
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $htmlBody HTML content for the email
     * @param string $plainBody Plain text alternative (optional)
     * @param array $attachments Optional array of attachments [path => filename]
     * @return bool Whether the email was sent successfully
     */
    public function send($to, $subject, $htmlBody, $plainBody = '', $attachments = [])
    {
        try {
            // Reset recipients
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();

            // Add recipient
            $this->mailer->addAddress($to);

            // Set content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $htmlBody;

            // Set plain text alternative if provided
            if (!empty($plainBody)) {
                $this->mailer->AltBody = $plainBody;
            }

            // Add attachments if any
            foreach ($attachments as $path => $name) {
                $this->mailer->addAttachment($path, $name);
            }

            // Send the email
            return $this->mailer->send();
        } catch (Exception $e) {
            // Log the error or handle it as needed
            error_log('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send an OTP email
     * 
     * @param string $email Recipient email
     * @param string $otp The OTP code
     * @param int $expiryMinutes Minutes until OTP expires
     * @return bool Whether the email was sent successfully
     */
    public function sendOTP($email, $otp, $expiryMinutes = 10)
    {
        $subject = 'Your OTP Code';
        $htmlBody = "Your OTP code is: <b>{$otp}</b><br>This code will expire in {$expiryMinutes} minutes.";
        $plainBody = "Your OTP code is: {$otp}. This code will expire in {$expiryMinutes} minutes.";

        return $this->send($email, $subject, $htmlBody, $plainBody);
    }

    /**
     * Send a welcome email
     * 
     * @param string $email Recipient email
     * @param string $name Recipient name
     * @return bool Whether the email was sent successfully
     */
    public function sendWelcome($email, $name)
    {
        $subject = 'Welcome to Evergreen Tea Factory';
        $htmlBody = "Hello <b>{$name}</b>,<br><br>Welcome to Evergreen Tea Factory! We're excited to have you on board.";
        $plainBody = "Hello {$name},\n\nWelcome to Evergreen Tea Factory! We're excited to have you on board.";

        return $this->send($email, $subject, $htmlBody, $plainBody);
    }

    public function sendFertilizerRequest($email, $name, $state)
    {
        if ($state == 'Accept') {
            $subject = "Evergreen Fertilizer Request";
            $htmlBody = "Hello <b>{$name}</b>,<br><br>Your fertilizer request has been accepted! You may collect it from our inventory. <br><br> Evergreen Tea Factory";

            // Define the plain text body
            $plainBody = "Hello {$name},\n\nYour fertilizer request has been accepted! You may collect it from our inventory.\n\nEvergreen Tea Factory";

            return $this->send($email, $subject, $htmlBody, $plainBody);
        } elseif ($state == 'Reject') {
            $subject = "Evergreen Fertilizer Request";
            $htmlBody = "Hello <b>{$name}</b>,<br><br>Your fertilizer request has been rejected. If you have any questions, please contact us.<br><br> Evergreen Tea Factory";

            // Define the plain text body
            $plainBody = "Hello {$name},\n\nYour fertilizer request has been rejected. If you have any questions, please contact us.\n\nEvergreen Tea Factory";

            return $this->send($email, $subject, $htmlBody, $plainBody);
        }
    }
}

?>
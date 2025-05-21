<?php
/**
 * PHP Email Form Library
 * Used for sending contact form messages via PHP's mail() function or SMTP
 */



 require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

class PHP_Email_Form {
  public $to = '';
  public $from_name = '';
  public $from_email = '';
  public $subject = '';
  public $smtp = false;
  public $ajax = false;

  private $messages = array();

  public function add_message($content, $label = '', $length = 0) {
    if (!empty($content)) {
      if ($length > 0) {
        $content = substr(trim($content), 0, $length);
      }
      $this->messages[] = "$label: $content\n";
    }
  }

  public function send() {
    $message = implode("\n", $this->messages);
    $headers = "From: " . $this->from_name . " <" . $this->from_email . ">\r\n";
    $headers .= "Reply-To: " . $this->from_email . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if ($this->smtp) {
      return $this->send_smtp($message, $headers);
    } else {
      return $this->send_mail($message, $headers);
    }
  }

  private function send_mail($message, $headers) {
    if (mail($this->to, $this->subject, $message, $headers)) {
      return 'OK';
    } else {
      return 'Error: Message could not be sent.';
    }
  }

  private function send_smtp($message, $headers) {
    if (!isset($this->smtp['host']) || !isset($this->smtp['username']) || !isset($this->smtp['password'])) {
      return 'Error: SMTP credentials not provided correctly.';
    }

    // Load PHPMailer
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer')) {
      require 'PHPMailer/PHPMailer.php';
      require 'PHPMailer/SMTP.php';
      require 'PHPMailer/Exception.php';
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = $this->smtp['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $this->smtp['username'];
    $mail->Password = $this->smtp['password'];
    $mail->SMTPSecure = isset($this->smtp['encryption']) ? $this->smtp['encryption'] : 'tls';
    $mail->Port = isset($this->smtp['port']) ? $this->smtp['port'] : 587;

    $mail->setFrom($this->from_email, $this->from_name);
    $mail->addAddress($this->to);
    $mail->Subject = $this->subject;
    $mail->Body = $message;

    if ($mail->send()) {
      return 'OK';
    } else {
      return 'Error: ' . $mail->ErrorInfo;
    }
  }
}
?>

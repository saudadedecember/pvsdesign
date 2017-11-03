<?php
class Mail {
  static $to = '';
  static $subject = 'Подтверждение регистрации';
  static $from = 'info@pvsdesign.esy.es';
  static $headers = array();
  static $message = '';
  
  static function Send($email) {
    
    self::$to = $email;
    // поддержка utf-8 заголовка
    self::$subject = '=?UTF-8?B?'.base64_encode(self::$subject).'?=';
    
    self::$headers[] = "MIME-Version: 1.0";
    self::$headers[] = "Content-type: text/html; charset=UTF-8";
    self::$headers[] = "From: pvsdesign <info@pvsdesign.esy.es>";
    self::$headers[] = "Subject: ".self::$subject;
    
    self::$message = '
    <html>
  <head>
  <title>Validation</title>
  </head>
  <body>
    <a href="http://pvsdesign.esy.es//index.php?module=entry&form=activate&id='.myHash($_POST['login'].'&'.$_POST['email']).'"  target="_blank">http://pvsdesign.esy.es//index.php?module=entry&form=activate&id=/'.myHash($_POST['login'].'&'.$_POST['email']).'</a>
  </body>
  </html>
  ';
    
    return mail(self::$to, self::$subject, self::$message, implode("\r\n", self::$headers));
  }
}

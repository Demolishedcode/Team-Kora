<?php
require "/../classes/PHPMailer-master/PHPMailerAutoload.php";
include_once "/../includes/kora_database.php";

// $error = null; // Define error for global scrope
$mail = new PHPMailer;

// Define Marsa email server
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.gmail.com';                      // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'demolishedcode@gmail.com';             // SMTP username
$mail->Password = 'ZebraOpZebraPad135';              // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to
$mail->isHTML(true);                                  // Set email format to HTML
$mail->setFrom('demolishedcode@gmail.com', 'Team Kora');

function sendActivation ($userName, $userId, $userMail, $userActivation) {
  global $mail;

  $url = "http://localhost/teamkora/activation?x=$userId&y=$userActivation";

  $mail->addAddress($userMail, $userName); // Add a recipient
  $mail->Subject = 'Activate account - Team Kora';
  $mail->Body    = "<html><head><link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet'></head><body style='padding:0;margin:0;'><div style='background-color:white;width:100%;font-family:'Source Sans Pro',sans-serif;'><div style='width:100%;background:rgb(36,42,67);padding:5px;margin-bottom:10px;color:#e0404f'><h1 style='margin-left:20px;'>Team Kora</h1></div><div style='width:100%;background-color:white;padding:5px;margin-bottom:10px;''><h3>Just one more step...</h3><h4 style='color:#e0404f;margin-left:10px;'>$userName</h4><div style='background-color:gray;height:2px;width:100%;'></div><h5 style='color:#737373'>Thanks for signin gup to Team Kora<br>Please activate your account.</h5><a href='$url' style='color:white;height:40px;width:150px;text-decoration:none'><div style='margin-left:10px;padding:5px;text-align:center;line-height:40px;height:40px;width:150px;background-color:#307cba;border-radius:5px;'>Activate Account</div></a><h5 style='color:#737373'>Have a great day,<br><span style='color:black'>Team Kora</span></h5></div></div></body></html>";

  if(!$mail->send()) {
    return false;
  } else {
    return true;
  }
}

function sendReset ($userMail) {
  global $mail, $pdo;

  // Get userName
  $user = $pdo->prepare("SELECT fname, mname, lname FROM user WHERE email= :email");
  $user->execute([
    'email' => $userMail,
  ]);
  $userName = $user->fetchAll();

  if (!empty($userName)) {

    foreach ($userName as $key) {
        $fname      = $key['fname'];
        $mname      = empty($key['mname']) ? "" : $key['mname'];
        $lname      = $key['lname'];
    }

    $fullName = $fname . ' ' . $mname . ' ' . $lname;
    $userReset = bin2hex(openssl_random_pseudo_bytes(16));

    $url = "http://localhost/teamkora/reset.php?x=$userMail&y=$userReset";

    $archiveReset = $pdo->prepare("UPDATE users_reset SET token=:resettoken, expire=CURRENT_TIMESTAMP WHERE user_id= :userId");
    $archiveReset->execute([
      'resettoken' => $userReset,
      'email'      => $userMail
    ]);

    $mail->addAddress($userMail, $fullName);
    $mail->Subject = 'Reset password - Team Kora';
    $mail->Body = "<html><head><link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet'></head><body style='padding:0;margin:0;'><div style='background-color:white;width:100%;font-family:'Source Sans Pro',sans-serif;'><div style='width:100%;background:rgb(36,42,67);padding:5px;margin-bottom:10px;color:#e0404f'><h1 style='margin-left:20px;'>Team Kora</h1></div><div style='width:100%;background-color:white;padding:5px;margin-bottom:10px;''><h3>Reset your password...</h3><h4 style='color:#e0404f;margin-left:10px;'>$fullName</h4><div style='background-color:gray;height:2px;width:100%;'></div><h5 style='color:#737373'>This mail contains a link to reset your password.<br>Dont wait to long the link will eventualy expire!</h5><a href='$url' style='color:white;height:40px;width:150px;text-decoration:none'><div style='margin-left:10px;padding:5px;text-align:center;line-height:40px;height:40px;width:150px;background-color:#307cba;border-radius:5px;'>Reset link</div></a><h5 style='color:#737373'>Have a great day,<br><span style='color:black'>Team Kora</span></h5></div></div></body></html>";

    return $mail->send() ? true : false;

  } else {
    return false;
  }
}

?>

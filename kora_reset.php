<!DOCTYPE html>
<html>
<?php
require_once "includes/kora_navigation.php"; // Add the navigation
require_once "includes/kora_sendmail.php"; // Add sendmail function
if (isset($_POST['submit-reset'])) {
  $email = escape($_POST['email']);

  $error = null;

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Invalid email adress';
  }

  if ($error) {
    echo "<div clas='error-box'>$error</div>";
  } else {
    // Check latest request, preventing endless request
    $request = $pdo->prepare("SELECT expire FROM user WHERE user_id= :userId");
    $request->execute([
      'userId' => $_SESSION['userId']
    ]);

    $time = $request->fetchAll();

    foreach($time as $key) {
      $timeRequest = $key['expire'];
      $timeRequest = empty($timeRequest) ? false  :  new DateTime($timeRequest);
      $timeRequest->modify('+5 minutes');
    }

    $timeCurrent = new DateTime();

    // What to do on first time
    if ($timeCurrent > $timeRequest || !$timeRequest) {
      if ($sendMail = sendReset($email)) {
        echo "<div class='succes-box'>Email succesfully send to: $email</div>";
      } else {
        echo "<div class='error-box'>Couldn't send email to: $email</div>";
      }
    } else {
      echo "<div class='error-box'>You can only request a password every 5 minutes!</div>";
    }
  }
}

function escape ($value) {
  $value = htmlspecialchars($value);
  $value = strip_tags($value);
  $value = trim($value);

  return $value;
}

?>
  <head>
    <meta charset="utf-8">
    <title>Team Kora | Reset</title>
  </head>
  <body>
      <div class="container-form center">
        <div class="form-title">
          Reset Password
        </div>
        <div class="form-content">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?submit=true" method="post" id="login">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
              <input type="email" name="email" value="" placeholder="Email Address" required>
            </div>
            <div class="input-group">
              <button type="submit" name="submit-reset" class="btn btn-primary">Send Link</button>
            </div>
          </form>
        </div>
      </div>
  </body>
</html>

<!DOCTYPE html>
<?php
require_once "includes/kora_navigation.php";
require_once "includes/kora_database.php"; // Database connection

$userMail = trim($_GET['x']);
$userToken = trim($_GET['y']);

$error = [];

if (!empty($userMail) && filter_var($userMail, FILTER_VALIDATE_EMAIL) && !empty($userToken)) {
  // Compare tokens
  $compare = $pdo->prepare("SELECT reset_token, reset_time FROM user WHERE email= :email");
  $compare->execute([
    'email' => $userMail
  ]);

  $compareData = $compare->fetchAll();

  foreach ($compareData as $key) {
    $token = $key['reset_token'];
    $time = $key['reset_time'];
    $time = explode(" ", $time);
  }

  $date = $time[0];
  $curDate = date('Y-m-d');

  if ($date !== $curDate) {
    echo "<div class='error-box'>The link has expired</div>";
    $removeToken = $pdo->prepare("UPDATE user SET reset_token='' WHERE email= :email");
    $removeToken->execute([
      'email' => $userMail
    ]);

    exit();
  }

  if ($token === '') {
    echo "<div class='error-box'>No valid token found! Please check your email-adress</div>";
    exit();
  }

  if ($token != $userToken) {
    echo "<div class='error-box'>Invalid request!</div>";
    exit();
  }

} else {
  echo "<div class='error-box'>Invalid request!</div>";
  exit();
}

if (isset($_POST['submit-reset'])) {
  $password = $_POST['password'];
  $c_password = $_POST['c_password'];
  // Check if passwords match
  if ($password != $c_password) {
    $error = "Passwords don't match";
  } else {
    // Check if password is longer than 7 chars
    // Check if password contains a number
    if (strlen($password) < 7 || ctype_alpha($password)) {
      $error = "Password must atleast contain 7 characters and one non-letter";
      $password = "";
    }
  }

  if ($error) {
    echo "<div class='error-box'>$error</div>";
  } else {
    $enc_password = password_hash($password, PASSWORD_DEFAULT);

    $change = $pdo->prepare("UPDATE user SET passw= :password, reset_token='' WHERE email= :email");
    $change->execute([
      'password' => $enc_password,
      'email' => $userMail
    ]);

    header("Location: login?action=reset");
  }
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Team Kora | Reset Password</title>
  </head>
  <body>
    <div class="container-form center">
      <div class="form-title">
        Reset Password
      </div>
      <div class="form-content">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . "?x=$userMail&y=$userToken";?>" method="post" id="login">
          <div class="input-group">
            <input type="text" name="password" placeholder='New password' required autofocus>
            <span class="input-group-addon" data-show=''><i class="glyphicon glyphicon-eye-open"></i></span>
          </div>
          <div class="input-group">
            <input type="text" name="c_password" placeholder='Confirm password' required>
            <span class="input-group-addon" data-show=''><i class="glyphicon glyphicon-eye-open"></i></span>
          </div>
          <div class="input-group">
            <button type="submit" name="submit-reset" class="btn btn-primary">Change password</button>
          </div>
        </form>
      </div>
    </div>
    <style>span:hover {cursor: pointer;}</style>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.input-group-addon').on('click', function(){
          var show   = $(this).data('show'),
              $input = $(this).parent().find('input');
              $i     = $(this).find('i');

          if (show === 'noshow') {
            $(this).data('show','show');
            $i.attr('class','glyphicon glyphicon-eye-close');
            $input.attr('type', 'text');
          } else if (show === 'show') {
            $(this).data('show','noshow');
            $i.attr('class','glyphicon glyphicon-eye-open');
            $input.attr('type', 'password');
          }
        });

        $('input').keyup(function(){
          var chars = $(this).val(),
              charCount = chars.length;

          if (charCount > 0) {
            $(this).parent().find('span').data('show','noshow');
            $(this).attr('type', 'password');
          } else {
            $(this).parent().find('span').data('show','');
            $(this).attr('type', 'text');
          }
        });
      });
    </script>
  </body>
</html>

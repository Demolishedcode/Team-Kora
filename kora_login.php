<!DOCTYPE html>
<html>
<?php
require_once "includes/kora_database.php"; // Connection to datbase
require_once "includes/kora_navigation.php"; // Add the navigation

$action = isset($_GET['action']) ? $_GET['action'] : "";

// Give user feedback on actions
if ($action == "register") {
  echo "<div class='succes-box'>Succesfully registered! To activate your account goto your E-mail and click on the link</div>";
} else if ($action == "active") {
  echo "<div class='succes-box'>Your account is activated! You can now login</div>";
} else if ($action == "reset") {
  echo "<div class='succes-box'>Password changed succesfully!</div>";
}

// Check if already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
  header('location: profile?action=login');
}

if (isset($_POST['submit-login']) && isset($_POST['token'])) {
  if ($_POST['token'] === $_SESSION['token']) {
    $email = strtolower(escape($_POST['email']));
    $passw = escape($_POST['password']);

    $error = [];

    if (isset($_POST['remember_email']) && $_COOKIE['_email'] !== $email) {
      echo "Set";
      $_COOKIE['_email'] = $email;
      setcookie('_email',$email, time() + (86400 * 30), '/');
    } elseif (!isset($_POST['remember_email']) && isset($_COOKIE['_email'])) {
      echo "unset";
      unset($_COOKIE['_email']);
      setcookie('_email', null, -1, '/');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      array_push($error, "Email is invalid");
    }

    if ($error) {
      echo "<div class='error-box'>";
      foreach ($error as $key => $value) {
        echo "<span>$value</span><br>";
      };
      echo "</div>";
    } else {
      $login = $pdo->prepare("SELECT user_id, email, passw, active, fname, mname, lname FROM user WHERE email= :email");
      $login->execute([
        'email' => $email,
      ]);
      $dataLogin = $login->fetchAll();

      foreach ($dataLogin as $key) {
          $userId     = $key['user_id'];
          $email      = $key['email'];
          $enc_passw  = $key['passw'];
          $active     = $key['active'];
          $fname      = $key['fname'];
      }

      if (password_verify($passw, $enc_passw)) {
        if ($active == 'yes') {
          $_SESSION['userId'] = $userId;
          $_SESSION['userName'] = $fname;
          $_SESSION['loggedin'] = true;

          header('location: profile?action=login');
          exit();
        } else {
          echo "<div class='error-box'>Your account is not activated yet! Check your email to activate your account</div>";
        }
      } else {
        echo "<div class='error-box'>Combination of E-mail and Password is invalid</div>";
      }
    }
  } else {
    echo "<div class='error-box'>Invalid login</div>";
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
    <title>Team Kora | Login</title>
  </head>
  <body>
      <div class="container-form center">
        <div class="form-title">
          Login
        </div>
        <div class="form-content">
          <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?submit=true" method="post" id="login">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
              <input type="email" name="email" placeholder="Email Address" <?php echo isset($_COOKIE['_email']) ? 'value=' . $_COOKIE['_email'] : null?> required autofocus>
            </div>
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
              <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-group">
              <input type="checkbox" name="remember_email" <?php echo isset($_COOKIE['_email']) ? 'checked' : null?>><span>Remember Email</span>
            </div>
            <div class="input-group">
              <input type="hidden" name="token" value="<?php echo genToken(); ?>">
              <button type="submit" name="submit-login" class="btn btn-primary">Login</button><a href="http://localhost/teamkora/forgot" class="link">Forgot Password</a>
            </div>
          </form>
        </div>
      </div>
  </body>
</html>

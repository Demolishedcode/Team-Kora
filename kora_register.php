<!DOCTYPE html>
<html>
<?php
require_once "includes/kora_navigation.php"; // Add the navigation
require_once "includes/kora_sendmail.php"; // be-able to send emails
require_once "includes/kora_database.php"; // Database connection

if (isset($_POST['submit-register']) && isset($_POST['token'])) {
  if ($_POST['token'] === $_SESSION['token']) {
    // Fetch data from post
    $fname      = ucfirst(strtolower(escape($_POST['f_name'])));
    $mname      = empty($_POST['m_name']) ? ""  : strtolower(escape($_POST['m_name']));
    $lname      = ucfirst(strtolower(escape($_POST['l_name'])));
    $fullName   = $fname . " " . $mname . " " . $lname;

    $email      = strtolower(escape($_POST['email']));
    $password   = escape($_POST['password']);
    $c_password = escape($_POST['c_password']);

    // Set array for getting errors
    $error = [];

    // Check if the full name is valid
    if (!preg_match("/^[a-z ,.'-]+$/i", $fullName)) {
      array_push($error, "Enter a valid name, only letters allowed");
      $fname = $mname = $lname = $fullName = "";
    }

    // Check if email is not already taken
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      array_push($error, "Email is invalid");
      $email = "";
    } else {
      $checkEmail = $pdo->prepare("SELECT email FROM user WHERE email= :email LIMIT 1");
      $checkEmail->execute(['email' => $email]);
      $check = $checkEmail->fetch();

      if ($check) {
        array_push($error, "Email is already taken");
        $email = "";
      }
    }

      // Check if passwords match
      if ($password != $c_password) {
        array_push($error, "Passwords don't match");
        $c_password = "";
      } else {
        // Check if password is longer than 7 chars
        // Check if password contains a number
        if (strlen($password) < 7 || ctype_alpha($password)) {
          array_push($error, "Password must atleast contain 7 characters and one non-letter");
          $password = $c_password = "";
        }
    }

    // Check if erros occurred
    if ($error) {
      echo "<div class='error-box'>";
        foreach ($error as $key => $value) {
          echo "<span>$value</span><br>";
        }
      echo "</div>";
    } else {
      // Hash password
      $enc_password = password_hash($password, PASSWORD_DEFAULT);

      // Activation id
      $activationId = md5(uniqid(rand(),true));

      // Register user to Database
      $register = $pdo->prepare("
      INSERT INTO users (firstName, middleName, lastName, email, password) VALUES (:fname, :mname, :lname, :email, :password, :active);
      INSERT INTO users-active ()
      ");
      $register->execute([
        'fname'    => $fname,
        'mname'    => $mname,
        'lname'    => $lname,
        'email'    => $email,
        'password' => $enc_password,
        'active'   => $activationId
      ]);

      // User id
      $userId = $pdo->lastInsertId();

      sendActivation($fullName, $userId, $email, $activationId);

      unset($fname, $mname, $lname, $email, $password, $c_password, $enc_password, $_POST);
      header('Location: login?action=register');
      exit();
    }
  } else {
    echo "<div class='error-box'>Invalid Register</div>";
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
    <title>Team Kora | Register</title>
  </head>
  <body>
    <div class="container-form center">
      <div class="form-title">
        Register
      </div>
      <div class="form-content">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" id="login">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" name="f_name" value='<?php echo isset($_POST["submit-register"]) ? $fname : '';?>' placeholder="First Name" required autofocus>
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" name="m_name" value='<?php echo isset($_POST["submit-register"]) ? $mname : '';?>' placeholder="Middle Name">
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" name="l_name" value='<?php echo isset($_POST["submit-register"]) ? $lname : '';?>' placeholder="Last Name" required>
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            <input type="email" name="email" value='<?php echo isset($_POST["submit-register"]) ? $email : '';?>' placeholder="Email Address" required>
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
            <input type="password" name="password" value='<?php echo isset($_POST["submit-register"]) ? $password : '';?>' placeholder="Password" required><br>
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-retweet"></i></span>
            <input type="password" name="c_password" value='<?php echo isset($_POST["submit-register"]) ? $c_password : '';?>' placeholder="Confirm Password" required>
          </div>
          <div class="input-group">
            <input type="hidden" name="token" value="<?php echo genToken();?>">
            <button type="submit" name="submit-register" class="btn btn-primary">Register</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>

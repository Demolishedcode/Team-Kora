<?php
session_start();
require_once "/../includes/kora_database.php";

if (isset($_POST['save'])) {

  $fname = ucfirst(strtolower(escape($_POST['fname'])));
  $lname = ucfirst(strtolower(escape($_POST['lname'])));
  $mname = empty($_POST['mname']) ? ""  : strtolower(escape($_POST['mname']));
  $fullName = $fname . ' ' . $mname . ' ' . $lname;

  $about = escape($_POST['about']);

  $email = strtolower(escape($_POST['email']));

  $error = [];

  if (!preg_match("/^[a-z ,.'-]+$/i", $fullName)) {
    array_push($error, "Enter a valid name, only letters allowed");
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    array_push($error, "Email is invalid");
  } else {
    $checkEmail = $pdo->prepare("SELECT email FROM user WHERE user_id=:id LIMIT 1");
    $checkEmail->execute(['id' => $_SESSION['userId']]);
    $check = $checkEmail->fetch();

    if ($check && $check['email'] != $email) {
      array_push($error, "Email is already taken");
    }
  }

  if ($error) {

    for($i = 0; $i < count($error); $i++) {
      echo $error[$i] . "<br>";
    }

  } else {
    $update = $pdo->prepare("
      UPDATE user
      SET fname=:fname, mname=:mname,
      lname=:lname, about=:about,
      email=:email
      WHERE user_id=:id"
    );

    $update->execute([
      'fname' => $fname,
      'mname' => $mname,
      'lname' => $lname,
      'about' => $about,
      'email' => $email,
      'id'    => $_SESSION['userId']
    ]);

    echo "Succesfully saved!";
  }
}

function escape ($value) {
  $value = strip_tags($value);
  $value = htmlspecialchars($value);
  $value = trim($value);

  return $value;
}
?>

<!DOCTYPE html>
<html>
<?php
require_once "includes/kora_navigation.php";
require_once "includes/kora_database.php"; // Database connection

$userId = trim($_GET['x']);
$activationId = trim($_GET['y']);

if (is_numeric($userId) && !empty($activationId)) {
  $setActive = $pdo->prepare("UPDATE user SET active = 'yes' WHERE user_id = :userid AND active = :activationId");
  $setActive->execute([
    'userid'       => $userId,
    'activationId' => $activationId
  ]);

  if ($setActive->rowCount() == 1) {
    header('Location: login?action=active');
    exit();
  } else {
    echo "<div class='error-box'>Your account could not be activated! Contact us for help</div>";
  }
} else {
  echo "<div class='error-box'>An Error has occurred!</div>";
}
?>
  <head>
    <meta charset="utf-8">
    <title>Team Kora | Activation </title>
  </head>
  <body>

  </body>
</html>

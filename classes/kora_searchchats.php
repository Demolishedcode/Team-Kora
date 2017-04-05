<?php
require_once "./../includes/kora_database.php";

if (isset($_POST["q"]) && !empty($_POST["q"])) {
  $q = $_POST["q"];

  $queryGroups = $pdo->prepare("SELECT name FROM chat_rooms WHERE name LIKE :q");
  $queryGroups->execute([
    "q" => '%' . $q . '%'
  ]);

  $queryChats = $pdo->prepare('SELECT fname FROM user WHERE fname LIKE :q');
  $queryChats = $pdo->execute([
    'q' => '%'. $q . '%'
  ]);

  $allGroups = "";
  $allChats  = "";

  $countGroups = $queryGroups->rowCount();

  if ($count > 0) {
    $results = $query->fetchAll();
    $index = 0;

    foreach ($results as $key => $val) {
      $allItems = "
        <input type='checkbox' class='select-item' name='item-$index'/>
        <label class='select-item-label h-cursor' for='item-$index'>" . $val['name']. "</label>
      ";

      $index++;
    }

    echo $allGroups;
  } else {
    echo "No chats found!";
  }
}
?>

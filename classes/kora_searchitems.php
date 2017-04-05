<?php
require_once "/../includes/kora_database.php";

if (isset($_POST['q'])) {
  $item = $_POST['q'];

  $search = $pdo->prepare("SELECT item_id, title, SUBSTRING(content,1,60) AS content, date FROM news WHERE title LIKE :key");
  $search->execute([
    'key' => "%" . $item . "%"
  ]);

  $count = $search->rowCount();
  $items = $search->fetchAll();

  $allItems = '';

  if ($count > 0 && !empty($item)) {
    foreach ($items as $key => $value) {
      $date = explode(" ", $value['date']);
      $date = $date[0];
      $date = new DateTime(str_replace("-","/",$date));

      $curdate = new DateTime();
      $curdate->format('Y/m/d');

      $diff = $curdate->diff($date);
      $diffDay = (int)$diff->format("%R%a");

      switch ($diffDay) {
        case 0:
          $date = 'Today';
          break;
        case -1:
          $date = 'Yesterday';
          break;
        default:
          $date = (string)$date->format('Y/m/d');
          break;
      }

      $allItems .= <<<HTML
      <a href='http://localhost/TeamKora/article/{$value['item_id']}' class='link-news'>
        <div class='news-item row' title='{$value['title']}'>
          <h4><b>{$value['title']}</b> - {$date}</h4>
          <h5>{$value['content']}...</h5>
          <hr>
        </div>
      </a>
HTML;
    }

    echo $allItems;
  } else {
    echo "No Items found!";
  }
}
?>

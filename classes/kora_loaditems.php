<?php
require_once "/../includes/kora_database.php";

if (isset($_POST['loadItems'])) {
  $start = isset($_POST['start']) ? (int)$_POST['start'] : 1;
  $max = isset($_POST['limit']) && $_POST['limit'] <= 10 ? (int)$_POST['limit'] : 4;

  $new = $pdo->prepare("SELECT item_id, title, SUBSTRING(content,1,60) AS content, date, user_id FROM news ORDER BY date DESC LIMIT {$start},{$max}");
  $new->execute();

  $newsItems = $new->fetchAll();

  if (!empty($newsItems)) {
    $allItems = "";

    foreach ($newsItems as $key => $value) {
      $get = $pdo->prepare("SELECT fname FROM user WHERE user_id = :id");
      $get->execute([
        'id' => $value['user_id']
      ]);

      $user = $get->fetch();
      $user = $user['fname'];

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
          <h4 class='no-gutters'><b>{$value['title']}</b> - {$date}</h4>
          <h6 class='no-gutters'>By {$user}</h6>
          <h5 class='no-gutters'>{$value['content']}...</h5>
          <hr>
        </div>
      </a>
HTML;
    }
    echo $allItems;
  } else {
    echo "No more items found!";
  }
}
?>

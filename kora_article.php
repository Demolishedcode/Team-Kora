<!DOCTYPE html>
<html>
<?php
require_once "includes/kora_navigation.php"; // Add the navigation
require_once "includes/kora_database.php";   // Connection to datbase
?>
  <head>
    <meta charset="utf-8">
    <title>Team Kora | Article</title>
  </head>
  <body>
    <section>
      <div class="row">
        <div class="col-lg-3 col-lg-offset-1 section" style="margin-top: 30px;padding:0">
          <div class="section-inner">
            <div class="section-title">
              Search
            </div>
            <div class="section-middle" style="height:360px">
              <div class="form-content" style="margin-bottom: 10px;">
                <form method="post" id="login">
                  <div class="input-group">
                    <input type="text" name="search" placeholder="Search..." id="search" onKeyUp="searchItems()" autofocus autocomplete="off">
                    <!-- <button type="submit" name="submit-search" class="btn btn-primary" id="search-submit">&#10003</button> -->
                  </div>
                </form>
              </div>
              <div class="search-results">
                <!-- show search items  -->
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6" style="margin-top: 30px;">
          <?php
          // Get article
          $articleId = $_GET['id'];

          $article = $pdo->query("
            SELECT news.*, user.fname
            FROM news
            INNER JOIN user
            ON user.user_id=news.user_id
            WHERE item_id={$articleId}
          ");

          $content = $article->fetch();

          if (!empty($content)) {
            $date = explode(" ", $content['date']);
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

            echo "<h1><b>{$content['title']}</b></h1>
            <h5>By {$content['fname']} - {$date}</h3>
            <hr style='border:0;height: 2px; background-color:#e7e7e7'>
            <h3>{$content['content']}</h3>";
          } else {
            header('location: teamkora/404');
          }
          ?>
        </div>
      </div>
    </section>
    <script>
      function searchItems() {
        var search = $("input[name='search']").val(),
            dataString = "q=" + search;

        $.ajax({
            type: "POST",
            url: "classes/kora_searchitems.php",
            data: dataString,
            cache: false,
            success: function (html) {
              $('.search-results').html(html);
            }
        });
      }

      $("form").submit(function(e){
          e.preventDefault(e);
      });
    </script>
  </body>
</html>

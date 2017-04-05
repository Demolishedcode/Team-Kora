<!DOCTYPE html>
<html>
<?php
require_once "includes/kora_navigation.php"; // Add the navigation
require_once "includes/kora_database.php"; // Connection to datbase

// If session is not set dont show anything
if (!isset($_SESSION['userName'])) {
  header('Location: login');
  exit();
}

// Get information about the user
$user = $pdo->prepare("SELECT fname, mname, lname, email, about FROM user WHERE user_id= :userid");
$user->execute([
  'userid' => $_SESSION['userId']
]);

$userData = $user->fetchAll();

foreach ($userData as $key => $value) {
    $fname      = $value['fname'];
    $mname      = empty($value['mname']) ? "" : $value['mname'];
    $lname      = $value['lname'];
    $email      = $value['email'];
    $about      = empty($value['about']) ? "Hi I am $fname! Nice meeting you" : $value['about'];
}

$fullName = $fname . ' ' . $mname . ' ' . $lname;

// Add new items
if (isset($_POST['submit-item'])) {
  $title = escape($_POST['title']);
  $text  = escape($_POST['text']);

  $item = $pdo->prepare("INSERT INTO news (title, content, user_id) VALUES (:title, :content, :user_id)");
  $item->execute([
    'title'   => $title,
    'content' => $text,
    'user_id' => $_SESSION['userId']
  ]);

  if ($item) {
    echo "<div class='succes-popup'>Item succesfully added!</div>";
  } else {
    echo "<div class='error-popup'>Something went wrong</div>";
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
    <title>Team Kora | <?php echo $_SESSION['userName']; ?></title>
  </head>
  <body onload="loadItems()">
    <div class="container-form center new-item" style="z-index: 12;">
      <div class="form-title">
        News Item
      </div>
      <div class="form-content">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>?submit=true" method="post" id="login">
          <div class="input-group">
            <input type="text" name="title" placeholder="Title" maxlength="200" required autofocus autocomplete="off">
          </div>
          <div class="input-group">
            <textarea name="text" rows="8" cols="80" placeholder="Text..." required></textarea>
          </div>
          <div class="input-group">
            <button type="submit" name="submit-item" class="btn btn-primary">Add</button><span class="close-lb link" onClick="closeItem(this)">Cancel</span>
          </div>
        </form>
      </div>
    </div>
    <div class="lightbox-bg"></div>
    <section>
      <div class="row">
        <div class="col-lg-4 col-sm-4 section">
           <div class="section-inner">
             <div class="section-title">
               Personal
             </div>
             <div class="section-middle">
               <div class="container">
                 <div class="pull-left avatar avatar-middle" style="margin-right: 20px;">
                  <img src="" alt="">
                 </div>
                 <div class="pull-left profile-data">
                   <h3 style="margin-bottom: -15px" class="no-gutters"><?php echo $fullName ?><h3>
                   <h5 style="margin-bottom: 20px;" class="no-gutters"><?php echo $email ?></h5>
                   <!-- Get user role -->
                   <h4 class="no-gutters"><span>&#9733</span><span style="margin-left: 10px;">Admin</span></h4>
                 </div>
               </div>
               <div class="container" style="margin-top: 20px">
                 <div class="profile-about">
                   <span><?php echo $about; ?></span>
                 </div>
               </div>
             </div>
             <div class="section-bottom text-right row-fluid">
               <div class="col-lg-12">
                 <span><a href="http://localhost/teamkora/edit/<?php echo $_SESSION['userId']; ?>">Edit Profile</a></span>
               </div>
             </div>
           </div>
        </div>
        <div class="col-lg-4 col-sm-4 section">
          <div class="section-inner">
            <div class="section-title">
              Settings
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-4 section news">
          <div class="section-inner">
            <div class="section-title">
              News
            </div>
            <div class="section-middle">

            </div>
            <div class="section-bottom row-fluid">
              <div class="col-lg-6 load-more">
                <span class='link' onclick="loadItems(true)">Load More</span>
              </div>
              <div class="col-lg-6 text-right">
                <span class="add-item link" onclick="openItem()">Add News Item</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script>
    var offset = 0,
        limit = 4;

    function loadItems (more) {
      if (more) {
        offset += limit;
      }

      var dataString = 'start=' + offset + '&limit=' + limit + '&loadItems=true';

      $.ajax({
          type: "POST",
          url: "classes/kora_loaditems.php",
          data: dataString,
          cache: false,
          success: function (html) {
            $('.news').find('.section-middle').append(html);
          },
          error: function (html) {
            $('body').append("<div class='error-popup'>No more items found!</div>");
          }
      });
    }

    // Remove popups
    function removePopup () {
      $('.succes-popup, error-popup').delay(2000).animate({opacity: '0'}, function(){
        $('.succes-popup, error-popup').remove();
      });
    }

    // Close new item tab
    function openItem () {
      $('.lightbox-bg').fadeIn(function() {
        $('.new-item').slideDown();
      });
    }

    // Close item tab
    function closeItem ($this) {
    $('.new-item').slideUp(function(){
        $('.lightbox-bg').fadeOut();
      });
    }
    </script>
  </body>
</html>

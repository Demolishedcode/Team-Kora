<!DOCTYPE html>
<?php session_start(); ?>
<html>
  <head>
    <base href="http://localhost/teamkora/">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="style/kora_login_style.css" type="text/css">
    <!-- Load Javascript & Bootstrap -->
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  </head>
  <body>
    <div class="navigation">
      <div class="pull-left navigation-name">
        <a href="http://localhost/teamkora/home">
          Team Kora
        </a>
      </div>
      <?php if (isset($_SESSION['userName'])): ?>
      <div class="pull-right navigation-dropdown">
        <ul>
          <li class="drop-trigger h-cursor">
            <span><?php echo $_SESSION['userName'] ?></span>
          </li>
          <div class="dropdown-content">
            <li>
              <a href="http://localhost/teamkora/profile">
                Profile
              </a>
            </li>
            <!-- <li>
              <a href="http://localhost/teamkora/setup-chat">
                Chat
              </a>
            </li> -->
            <li>
              <a href="http://localhost/teamkora/logout">
                Logout
              </a>
            </li>
          </div>
        </ul>
      </div>
      <?php else: ?>
      <div class="pull-right navigation-links">
        <a href="http://localhost/teamkora/login">Login</a><a href="http://localhost/teamkora/register">Register</a>
      </div>
      <?php endif?>
      <script type="text/javascript">
        $(document).ready(function() {
          $('.drop-trigger').click(function(){
            var drop = $(this).data('drop');

            $(this).parent().find('.dropdown-content').slideToggle();
          });
        });
      </script>
    </div>
  </body>
</html>

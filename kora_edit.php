<!DOCTYPE html>
<?php
require_once "includes/kora_navigation.php";
require_once "includes/kora_database.php";

$id = $_GET['id'];

if (is_numeric($id) && $_SESSION['userId'] === $id) {

  $getData = $pdo->query("SELECT fname, mname, lname, about, email FROM user WHERE user_id='$id'");
  $data    = $getData->fetchAll();

  print_r($data);

} else {
  header('location: ./../404');
}
?>
<html>
  <head>
    <meta charset="utf-8">
  </head>
  <body>
    <div class="container center">
      <h4>Public information</h4>
      <form action="self" method="post">
        <div class="row no-gutters">
          <div class="col-lg-2">
            <div class="avatar avatar-big" style="width: 150px; height: 150px;">
              <img src="" alt="">
            </div>
            <span class='link' onclick="togglePopup()">Change Avatar</span>
            <div class="popup pic-change-popup hidden" style="margin-top: -20px;">
              <h4 class="no-gutters">Change Avatar</h4>
              <hr>
              <input type="file" name="avatar" value="">
              <h6><i>Only <b>jpg</b> and <b>png</b> files allowed</i></h6>
              <hr>
              <span class="pull-right link" onclick="togglePopup()">Close</span>
            </div>
          </div>
          <div class="col-lg-10">
            <div class="input-group">
              <label for="fname">First name</label>
              <input type="text" name="fname" value="<?php echo $data[0]['fname'] ;?>" maxlength="100" required>
              <label for="mname">Middle name</label>
              <input type="text" name="mname" value="<?php echo $data[0]['mname'] ;?>" maxlength="100">
              <label for="lname">Last name</label>
              <input type="text" name="lname" value="<?php echo $data[0]['lname'] ;?>" maxlength="100" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="input-group">
            <label for="description">About me</label>
            <textarea name="description" rows="8" cols="80" placeholder="Express yourself"><?php echo $data[0]['about'] ;?></textarea>
          </div>
        </div>
        <h4>Private Information</h4>
        <div class="row input-group">
          <label for="email">Email adress</label>
          <input type="email" name="email" value="<?php echo $data[0]['email'] ;?>" placeholder='Email' required>
        </div>
        <div class="row no-gutters">
          <div class="col-lg-12">
            <input type="button" name="submit-save" onclick='saveProfile()' value="Save profile" class="btn btn-primary"><span style="margin-left: 20px"><a href="http://localhost/teamkora/profile">Cancel</a></span>
          </div>
        </div>
      </form>
    </div>
    <script type="text/javascript">
      function togglePopup () {
        $('.popup').toggleClass('hidden');
      }

      function saveProfile () {

        var firstName   = $("input[name='fname']").val(),
            lastName    = $("input[name='lname']").val(),
            middleName  = $("input[name='mname']").val(),
            description = $("textarea[name='description']").val(),
            email       = $("input[name='email']").val();

        var dataString = "save=true&fname=" + firstName + "&lname=" + lastName + "&mname=" + middleName + "&about=" + description + "&email=" + email;

        $.ajax({
            type: "POST",
            url: "classes/kora_save.php",
            data: dataString,
            cache: false,
            success: function (html) {
              $('body').append("<div class='succes-popup'>" + html + "</div>");
            }
        });
      }
    </script>
  </body>
</html>

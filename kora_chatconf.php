<!DOCTYPE html>
<html>
<?php
require_once "includes/kora_navigation.php";
require_once "includes/kora_database.php";

if (!isset($_SESSION['userId'])) {
  echo "<div class='error-box'>You're not logged in!</div>";
  exit();
}
?>
  <head>
    <title>Team Kora | Chat Config</title>
  </head>
  <body>
    <section>
      <div class="row">
        <form class="" action="index.html" method="post">
          <div class="col-lg-3 col-lg-offset-2 section">
            <div class="section-inner">
              <div class="section-title">
                Settings
              </div>
              <div class="section-middle text-center">
                <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                  <input type="text" name="display_name" value="" placeholder="Display name" maxlength="35">
                </div>
                <h5 class="sub-title">This name will be shown publicly</h5>
              </div>
            </div>
          </div>
          <div class="col-lg-4 section">
            <div class="section-inner">
              <div class="section-title">
                Choose Chat
              </div>
              <div class="section-middle">
                <h5 class="link" id="search" onclick="openSection(this.id)">Search for a chat</h5>
                <div class="section-container" data-show='false'>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                    <input onKeyUp="searchChat()" type="text" name="searchChats" placeholder="Search..." maxlength="35">
                    <span class='input-group-btn'><button type="button" name="select" class="btn btn-primary" style="height: 42px;">Join</button></span>
                  </div>
                  <div class="search-results checkboxes">
                    <!-- Search results -->
                  </div>
                </div>
                <hr/>
                <h5 class="link" id='create' onclick="openSection(this.id)">Create a chat</h5>
                <div class="section-container hidden" data-show='false'>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
                    <input type="text" name="create" value="" placeholder="Name" maxlength="35">
                  </div>
                  <div class="input-group">
                    <button type="button" name="create" class='btn btn-primary'>Create Group</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section>
    <script>
      function searchChat() {
        var search = $("input[name='searchChats']").val(),
            dataString = "q=" + search;

        $.ajax({
            type: "POST",
            url: "classes/kora_searchchats.php",
            data: dataString,
            cache: false,
            success: function (html) {
              $('.search-results').html(html);
            }
        });
      }

      function openSection (id) {
        var obj = $('#' + id).next(),
           show = obj.data('show');

        $('.section-container').addClass('hidden');
        obj.toggleClass('hidden');
      }

      $('.search-results').on('click','.select-item-label',function(){
        var labelAttr = $(this).attr('for'),
            $checkBox = $('body').find("input[name='" + labelAttr + "']");
            checked   = $checkBox.is(':checked');

        $('.select-item').prop('checked',false);
        $('.select-item-label').find('span').remove();

        $checkBox.prop('checked',true);
        $(this).append('<span>✔</span>');

        if (checked) {
          $checkBox.prop('checked',false);
          $(this).find('span').remove();
        }
      });
    </script>
  </body>
</html>

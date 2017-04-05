<!DOCTYPE html>
<html>
<?php
require_once "includes/kora_navigation.php";
require_once "includes/kora_database.php";

@include_once "classes/kora_sidechats";
@include_once "classes/kora_favchats"
?>
  <head>
    <meta charset="utf-8">
    <title>Team Kora | Chat</title>
  </head>
  <body>
   <section>
     <div class="row">
       <div class="col-lg-3">
         <div class="row">
           <div class="section">
             <div class="section-inner chat-profile">
               <div class="section-title">
                 Profile
               </div>
               <div class="section-middle">
                 <div class="avatar avatar-middle pull-left" style="margin-right: 10px">
                   <img src="" alt="">
                 </div>
                 <div class="chat-data">
                   <h3 class="no-gutters">Sander Aalbers</h3>
                   <h5 class="no-gutters sub-title" style="margin-bottom: 10px">Online</h5>
                   <h4 class="no-gutters">Owner</h4>
                 </div>
               </div>
             </div>
           </div>
         </div>
         <div class="row">
           <div class="section">
             <div class="section-inner chat-side">
               <div class="section-title">
                 Chats
               </div>
               <div class="section-middle">
                 <div class="news-item row h-cursor">
                   <div class="avatar avatar-small img-circle pull-left" style="margin-right: 10px">
                     <img src="" alt="">
                   </div>
                   <h4 class="no-gutters">Emma Verlaat</h4>
                   <h5 class="no-gutters sub-title">Hey hoe was het...</h5>
                   <hr>
                 </div>
                 <div class="news-item row h-cursor">
                   <div class="avatar avatar-small img-circle pull-left" style="margin-right: 10px">
                     <img src="" alt="">
                   </div>
                   <h4 class="no-gutters">Emma Verlaat</h4>
                   <h5 class="no-gutters sub-title">Hey hoe was het...</h5>
                   <hr>
                 </div>
               </div>
             </div>
           </div>
         </div>
       </div>
       <div class="col-lg-9 col-lg-offset-3 section chat" style="margin-left: -20px">
         <div class="section-inner">
           <div class="section-title">
             <div class="avatar avatar-small img-circle pull-left" style="margin-right: 10px;">
               <img src="" alt="">
             </div>
             <div class="chat-info">
               <h4 class='no-gutters'>Emma Verlaat</h4>
               <h5 class='no-gutters sub-title'>Offline</h5>
             </div>
           </div>
           <div class="section-middle">
             <!-- See messages -->
             <!-- <div class="chat-item-container">
              <div class="chat-item you">
                How are you doing?
              </div>
             </div>
             <div class="chat-item-container">
               <div class="chat-item other">
                 Hi! How are you doing?
               </div>
             </div> -->
           </div>
           <div class="section-bottom">
             <div class="chat-input input-group">
               <input type="text" name="chat-msg" value="" placeholder="Type a message..." spellcheck="true">
               <span class='input-group-btn'><button type="button" name="select" class="btn btn-primary" style="height: 45px;">Send</button></span>
             </div>
           </div>
         </div>
       </div>
     </div>
   </section>
  </body>
</html>

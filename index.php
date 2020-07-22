<?php 
require_once "includes/header.php"; 
require_once "includes/classes/VideoPlayer.php";

$videoGrid = new VideoGrid($con,$userLoggedInObj);
$subscriptionsProvider = new SubscriptionsProvider($con,$userLoggedInObj);
$subscriptionsVideos   = $subscriptionsProvider->getVideos();


?>

             <div id="mainContentContainer">
                  <div class="videoSection">
  
                  <?php

                     if(User::isLoggedIn() && !empty($subscriptionsVideos)) {

                      

                        echo $videoGrid->create($subscriptionsVideos,"الاشتراكات",false);

                       
                    }

                        echo $videoGrid->create(null,"مثاطع فيديو موصى بها",false);

                        echo $_SERVER['QUERY_STRING'];

                  ?>

                  </div>
             </div>
         </div>
 <?php require_once "includes/footer.php"; ?>

<?php 
require_once "includes/header.php";
require_once "includes/classes/VideoPlayer.php";
require_once "includes/classes/VideoInfoSection.php";
require_once "includes/classes/CommentSection.php";
require_once "includes/classes/Comment.php";


if(!isset($_GET['id']) || !is_numeric($_GET['id']) ) {

    $noUrl = "there is no url set ";

  
} else {

  $video = new Video($con,$_GET['id'],$userLoggedInObj);

  $views = $video->incrementViews();
  
  $videoPlayer = new VideoPlayer($video);
  
  $filePath =  $videoPlayer->create(true);
  
  $videoInfo = new VideoInfoSection($con,$video,$userLoggedInObj);

  $commentSection = new CommentSection($con,$video,$userLoggedInObj);

  $videoGrid = new VideoGrid($con,$userLoggedInObj);


  }







?>
         <div id="mainSectionContainer">
             <div id="mainContentContainer">

               <div class="watchLeftColumn">
                <?php
                
                 $filePath = isset($filePath) ? $filePath : "";
                 echo $filePath;
               
                
                if(isset($videoInfo)) {
                  echo $videoInfo->create();
                  ?>

                
                  
                  <div class='largscreen'><?php 
                    $comments = $commentSection->create();
                  
                  ?>
                  </div>
                 
               <?php } else {
                  echo $noUrl;
                  exit();
                }

                 
                 ?>
            

               </div>

               <div class="suggestions">

               <?php  echo $videoGrid->create(null,null,false); 

                
               ?>
                   
               </div>
            
               <div class="small-screen" style='display: none;'>
               <?php   $commentSection->create();
               ?>
               </div>
           
             </div>
         </div>
 <?php require_once "includes/footer.php"; ?>
 <script src="assets/js/videoplayeraction.js"></script>
 <script src="assets/js/commentActions.js"></script>

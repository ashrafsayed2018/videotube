<?php 
require_once "includes/header.php";
require_once "includes/classes/VideoPlayer.php";
require_once "includes/classes/VideoDetailsFormProvider.php";
require_once "includes/classes/VideoUploadData.php";
require_once "includes/classes/SelectThumbnail.php";


if(!User::isLoggedIn()) {
    header("Location :signin.php");
}

if(!isset($_GET['videoId']) || $_GET['videoId'] ==  "") {

    $noVideoSelected = "<span class='alert alert-danger text-center'> No Video Selected </span>";




} else {

    $videoId = $_GET['videoId'];
    $video = new Video($con,$videoId,$userLoggedInObj);
                       
    if($video->getUploadedBy() != $userLoggedInObj->getUserName()) {
        $notYourVideo = "<span class='alert alert-danger text-center'> this Video not your's </span>";
    }

    $formProvider = new VideoDetailsFormProvider();
}

if(isset($_POST['saveButton'])) {

    $videoTitle = $_POST['titleInput'];
    $videoDes   = $_POST['descriptionInput'];
    $videoPrivacy = $_POST['privacyInput'];
    $videoCat     = $_POST['categoryInput'];
    $uploadedBy   = $userLoggedInObj->getUserName();

    $videoData = new VideoUploadData(null,$videoTitle,$videoDes,$videoPrivacy,$videoCat,$uploadedBy);

    if($videoData->updateDetails($con,$video->getVideoId())) {
        $detaislMessage = "<div class='alert alert-success text-center'> Success : Details Updated Successfuly </div>";
        $video = new Video($con,$videoId,$userLoggedInObj);
     } else  {
           
             $detaislMessage = "<div class='alert alert-danger text-center'> Failed : Somthing went wrong  </div>";
     }
}




?>

<div id="mainSectionContainer">
             <div id="mainContentContainer">
                  <div class="largeVideoGridContainer">
                        <div class="editVideoContainer column">
                            <div class="topSection">
                                <?php 
                                  $videoPlayer = new VideoPlayer($video);

                                  echo $videoPlayer->create(false);

                                  $selectThumbnail = new SelectThumbnail($con,$video);
                                 echo $selectThumbnail->create();
                                
                                ?>
                            </div>
                            <div class="botttomSection">
                              <h3 class="text-center">تحديث بيانات الفيديو </h3>
                            <?php 
                                if(isset($formProvider)) {
                                    echo $detaislMessage = isset($detaislMessage) ? $detaislMessage : "";
                                    echo $formProvider->createEditDetailsForm($video);
                                }
                                ?>
                            </div>
                        </div>
  
                  <?php
                       echo $noVideoSelected = isset($noVideoSelected) ? $noVideoSelected : "";
                       echo $notYourVideo = isset($notYourVideo) ? $notYourVideo : "";
                      
                       

                    

                   
                  ?>

                  </div>
             </div>
         </div>


         
<?php


require_once "includes/footer.php";
?>
 <script src="assets/js/editVideoAction.js"></script>


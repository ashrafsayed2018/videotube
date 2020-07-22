<?php 
require_once "includes/header.php";
require_once "includes/classes/VideoUploadData.php";
require_once "includes/classes/VideoProcessor.php";

if(!isset($_SESSION['userLoggedIn'])) {
  header("Location:signin.php");
}

if(!isset($_POST['uploadButton'])) {
    echo " no file was sent to the page !!";
    exit();
}

// create file upload data 

$videoUploadData = new VideoUploadData($_FILES['fileInput'],$_POST['titleInput'],$_POST['descriptionInput'],$_POST['privacyInput'],$_POST['categoryInput'],$userLoggedInObj->getUserName());
// process video data (upload)

$videoProcessor = new VideoProcessor($con);

$wasSuccessful = $videoProcessor->upload($videoUploadData);
// check if upload was successful



 ?>
    <div id="mainSectionContainer">
             <div id="mainContentContainer">

              <?php 
              if($wasSuccessful) {
                echo "<span class='successMessage'>uplaod successful </span>";
            }
              ?>    
             </div>
         </div>

<?php require_once "includes/footer.php"; ?>
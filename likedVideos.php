<?php
require_once "includes/header.php";
require_once "includes/classes/LikedVideosProvider.php";

if(!User::isLoggedIn()) {
    header("Location:signin.php");
}

$LikedVideosProvider = new LikedVideosProvider($con,$userLoggedInObj);

$videos = $LikedVideosProvider->getVideos();

$videoGrid = new VideoGrid($con,$userLoggedInObj);



?>
    <div id="mainSectionContainer">
             <div id="mainContentContainer">
                <div class="largeVideoGridContainer">

                    <?php  
                            if(sizeof($videos) > 0) {
                            echo $videoGrid->createLarge($videos,"فيديوهات اعجبتك",false);

                            } else {
                                echo "<span class='errorMessage'> لا توجد اي فيديوهات اعجبتك حتي الان </span>";
                            }
                        
                    ?>
                </div>
             </div>
         </div>

<?php
require_once "includes/footer.php";

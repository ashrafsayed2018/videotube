<?php
require_once "includes/header.php";
require_once "includes/classes/SubscriptionsProvider.php";

if(!User::isLoggedIn()) {
    header("Location:signin.php");
}

$subscriptionsProvider = new SubscriptionsProvider($con,$userLoggedInObj);

$videos = $subscriptionsProvider->getVideos();

$videoGrid = new VideoGrid($con,$userLoggedInObj);



?>
    <div id="mainSectionContainer">
             <div id="mainContentContainer">
                <div class="largeVideoGridContainer">

                    <?php  
                            if(sizeof($videos) > 0) {
                            echo $videoGrid->createLarge($videos,"فيديوهات جديده من القنوات المشترك بها",false);

                            } else {
                                echo "<span class='errorMessage'> لا يوجد فيديوهات جديده للعرض </span>";
                            }
                        
                    ?>
                </div>
             </div>
         </div>

<?php
require_once "includes/footer.php";

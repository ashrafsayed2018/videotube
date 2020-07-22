<?php
require_once "includes/header.php";
require_once "includes/classes/TrendingProvider.php";

$trendingVideos = new TrendingProvider($con,$userLoggedInObj);

$videos = $trendingVideos->getVideos();

$videoGrid = new VideoGrid($con,$userLoggedInObj);



?>
    <div id="mainSectionContainer">
             <div id="mainContentContainer">
                <div class="largeVideoGridContainer">

                    <?php  
                            if(sizeof($videos) > 0) {
                            echo $videoGrid->createLarge($videos,"المحتوي الرائج في اخر سبع ايام ",false);

                            } else {
                                echo "<span class='errorMessage'> لا يوجد محتوي رائج للعرض </span>";
                            }
                        
                    ?>
                </div>
             </div>
         </div>

<?php
require_once "includes/footer.php";

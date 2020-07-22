<?php 
require_once "includes/header.php";
require_once "includes/classes/SearchResultsProvider.php";



if(!isset($_GET['term']) || $_GET['term'] ==  "") {
   echo "
   <br>
   <br>
   <br>
   <br>
  <h3 class='errorMessage'> يجب ادخال عباره للبحث عنها </h3>";

   exit();
} else {

    $term = $_GET['term'];

    if(!isset($_GET['orderBy']) || $_GET['orderBy'] == "views") {

        $orderBy = "views";


    } else {
        $orderBy = "uploadDate";

    }
 
}

$searchResultsProvider = new SearchResultsProvider($con,$userLoggedInObj);

$videos = $searchResultsProvider->getVideos($term,$orderBy);
$videoGrid = new VideoGrid($con,$userLoggedInObj);



?>

<div id="mainSectionContainer">
             <div id="mainContentContainer">
                  <div class="largeVideoGridContainer">
  
                  <?php

                      if(sizeof($videos) > 0) {
                        echo $videoGrid->createLarge($videos,sizeof($videos) . " نتائج تم ايجادها  ",true);
                      } else {
                          echo "<h3 class='errorMessage'> لا توجد فيديوهات للعرض  </h3>";
                      }

                      

                  ?>

                  </div>
             </div>
         </div>
<?php


require_once "includes/footer.php";
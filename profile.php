<?php
require_once "includes/header.php";
require_once "includes/classes/ProfileGenerator.php";

if(isset($_GET['username'])) {
    $profileUsername = $_GET['username'];
}

$profileGenerator = new ProfileGenerator($con,$userLoggedInObj,$profileUsername);



?> 

<div id="mainSectionContainer">
             <div id="mainContentContainer">
  
                  <?php
                    echo $profileGenerator->create();

                  ?>
             </div>
         </div>

<?php require_once "includes/footer.php";?>

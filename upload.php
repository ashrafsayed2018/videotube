<?php 
require_once "includes/header.php";
require_once "includes/classes/VideoDetailsFormProvider.php";

if(!isset($_SESSION['userLoggedIn'])) {
  header("Location:signin.php");
}
 ?>
         <div id="mainSectionContainer">
             <div id="mainContentContainer">
               <div class="column">
                   <?php 
                   $formProvider = new VideoDetailsFormProvider();
                   echo $formProvider->createUploadForm();
                   ?>
               </div>

                  
             </div>
         </div>

         <!-- Modal -->
<div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="loading" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        please wait while uploading is finishing ... 
        <img src="assets/images/icons/loading-spinner.gif" alt="">
      </div>
    </div>
  </div>
</div>
 <?php require_once "includes/footer.php"; ?>
 <script>

 $("form").submit(function() {
     $("#loading").modal("show");

 })
 </script>

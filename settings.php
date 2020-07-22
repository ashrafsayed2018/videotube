<?php
require_once "includes/header.php";
require_once "includes/classes/Account.php";
require_once "includes/classes/Account.php";
require_once "includes/classes/Constants.php";
require_once "includes/classes/FormSanitizer.php";
require_once "includes/classes/SettingsFormProvider.php";


if(!User::isLoggedIn()) {
    header("location:signin.php");
}
$detaislMessage = "";
$passwordMessage = "";
$settingsForm = new SettingsFormProvider();
$account = new Account($con);

$firstname =   isset($_POST['firstaName']) ? $_POST['firstaName'] : $userLoggedInObj->getFirstName();
$lastname =   isset($_POST['lastName']) ? $_POST['lastName'] : $userLoggedInObj->getLastName();
$email =   isset($_POST['email']) ? $_POST['email'] : $userLoggedInObj->getUserEmail();

if(isset($_POST['saveDetailsButton'])) {

    $firstName = FormSanitizer::sanitizeFormString($firstname);
    $lastName  = FormSanitizer::sanitizeFormString($lastname);
    $email     = FormSanitizer::sanitizeFormString($email);

    if($account->updateDetails($firstName,$lastName,$email,$userLoggedInObj->getUserName())) {
       $detaislMessage = "<div class='alert alert-success text-center'> Success : Details Updated Successfuly </div>";
    } else  {
           $errorMessage = $account->getFirstError();
            $detaislMessage = "<div class='alert alert-danger text-center'> Failed : $errorMessage </div>";
    }

}

if(isset($_POST['UpdatePassword'])) {

    $oldPassword     = FormSanitizer::sanitizeFormPassword($_POST['oldPassword']);
    $newPassword     = FormSanitizer::sanitizeFormString($_POST['newPassword']);
    $confirmPassword = FormSanitizer::sanitizeFormString($_POST['newPassword2']);

    if($account->updatePassword($oldPassword,$newPassword,$confirmPassword,$userLoggedInObj->getUserName())) {
       $passwordMessage = "<div class='alert alert-success text-center'> Success : password Updated Successfuly </div>";
    } else  {
           $errorMessage = $account->getFirstError();
            $passwordMessage = "<div class='alert alert-danger text-center'> Failed : $errorMessage </div>";
    }
}

?>
     <div id="mainSectionContainer">
             <div id="mainContentContainer">
                  <div class="settingsContainer column">
                        <div class="formSection">
                            <div class="message">
                                 <?php echo $detaislMessage;?>
                            </div>
                            <?php echo $settingsForm->createUserDetailsForm($firstname,$lastname,$email);?>
                             
                        </div>
                        <div class="formSection">
                            <div class="message">
                                <?php   echo $passwordMessage;?>
                            </div>
                            <?php echo $settingsForm->createPasswordForm(); ?>
                       </div>
                  </div>
             </div>
         </div>

<?php

require_once "includes/footer.php";

?>
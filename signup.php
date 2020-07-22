<?php 

require_once "includes/config.php"; 
require_once "includes/classes/FormSanitizer.php"; 
require_once "includes/classes/Account.php";
require_once "includes/classes/Constants.php";

$account = new Account($con);

if(isset($_POST['submitButton'])) {

    $firstname       = FormSanitizer::sanitizeFormString($_POST['firstName']);
    $lastname        = FormSanitizer::sanitizeFormString($_POST['lastName']);

    $username        = FormSanitizer::sanitizeFormUsername($_POST['username']);

    $email           = FormSanitizer::sanitizeFormEmail($_POST['email']);
    $email2          = FormSanitizer::sanitizeFormEmail($_POST['confirmEmail']);

    $password        = FormSanitizer::sanitizeFormPassword($_POST['password']);
    $password2       = FormSanitizer::sanitizeFormPassword($_POST['confirmPassword']);


    $wasSuccessful = $account->register($firstname,$lastname,$username,$email,$email2,$password,$password2);

    if($wasSuccessful) {
        // success

        $_SESSION['userLoggedIn'] = $username;

        // redirect to index page 
        header("Location:index.php");
    }
    
}

// function to get the input names 

function getInputValue($name) {
    
    if(isset($_POST[$name])) {
        echo $_POST[$name];
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir='rtl'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>sign up </title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div class="singUpContainer">
            <div class="column">
               <div class="header">
               <img src="assets/images/icons/VideoTubeLogo.png" alt="logo" title="logo">
                <h3> تسجيل  </h3>
                <span>للمتابعه لاعلان تيوب</span>
               </div>
               <div class="loginForm">
                    <form action="signup.php" method="POST">
                           
                            <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                            <input type="text" name="firstName" value="<?= getInputValue("firstName");?>"  placeholder="الاسم الاول " autocomplete="off" >

                            <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                            <input type="text" name="lastName" value="<?= getInputValue("lastName");?>"  placeholder="الاسم الاخير " autocomplete="off" >
                            <?php echo $account->getError(Constants::$usernameCharacters); ?>
                            <?php echo $account->getError(Constants::$usernameTaken); ?>
                            <input type="text" name="username" value="<?= getInputValue("username");?>" placeholder="اسم المستخدم" autocomplete="off" >
                            
                            <?php echo $account->getError(Constants::$emailCharacters); ?>
                            <?php echo $account->getError(Constants::$emailsNotMatching); ?>
                            <?php echo $account->getError(Constants::$emailTaken); ?>
                            <?php echo $account->getError(Constants::$emailInvalid); ?>
                             <input type="email" name="email" value="<?=getInputValue("email");?>" placeholder="الايميل  " autocomplete="off" >
      
                            <input type="email" name="confirmEmail" value="<?= getInputValue("confirmEmail");?>" placeholder="تأكيد ايميلك " autocomplete="off" >
         
                            
                            <?php echo $account->getError(Constants::$passwordCharacters); ?>
                            <?php echo $account->getError(Constants::$passwordsNotMatching); ?>
                            <?php echo $account->getError(Constants::$passwordOnlyLettersAndDigits); ?>
                            <input type="password" name="password" value="<?= getInputValue("password")?>" placeholder="الرقم السري  " autocomplete="off" >
                
                             <input type="password" name="confirmPassword" value="<?= getInputValue("confirmPassword");?>" placeholder="تأكيد الرقم السري  " autocomplete="off" >
              
                            <input type="submit" name="submitButton" class="btn btn-primary" value="تسجيل">
                    
                    </form>
               </div>
               <a href="signin.php" class="siginMessage">لديك حساب بالفعل ؟ دخول لحسابك</a>
            </div>
        </div>
    </body>
</html>
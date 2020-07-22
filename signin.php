<?php
require_once "includes/config.php"; 
require_once "includes/classes/FormSanitizer.php"; 
require_once "includes/classes/Account.php";
require_once "includes/classes/Constants.php";

$account = new Account($con);

if(isset($_POST['submitButton'])) {


    $username        = FormSanitizer::sanitizeFormUsername($_POST['username']);

    $password        = FormSanitizer::sanitizeFormPassword($_POST['password']);


    $wasSuccessful = $account->login($username,$password);

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
<html lang="en" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>sign in </title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div class="singUpContainer">
            <div class="column">
               <div class="header">
               <img src="assets/images/icons/VideoTubeLogo.png" alt="logo" title="logo">
                <h3>تسجيل دخول  </h3>
                <span>للمتابعه لاعلان تيوب </span>
               </div>
               <div class="loginForm">
                    <form action="signin.php" method="POST">
                            <?php echo $account->getError(Constants::$usernameCharacters); ?>
                            <?php echo $account->getError(Constants::$usernameTaken); ?>
                            <?php echo $account->getError(Constants::$loginFailed); ?>
                            <input type="text" name="username" value="<?= getInputValue("username");?>" placeholder="اسم المستخدم" autocomplete="off" >
         
                            <input type="password" name="password" value="<?= getInputValue("password");?>" placeholder="الرقم السري " autocomplete="off" >
              
                            <input type="submit" name="submitButton" class="btn btn-primary" value="دخول ">
                    
                    </form>
               </div>
               <a href="signup.php" class="siginMessage"> لا تملك حساب ؟ انشاء حساب جديد</a>
            </div>
        </div>
    </body>
</html>
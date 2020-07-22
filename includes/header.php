<?php 
require_once "config.php";
require_once "includes/classes/User.php";
require_once "includes/classes/ButtonProvider.php";
require_once "includes/classes/Video.php";
require_once "includes/classes/VideoGrid.php";
require_once "includes/classes/VideoGridItem.php";
require_once "includes/classes/SubscriptionsProvider.php";
require_once "includes/classes/NavigarionMenuProvider.php";


$usernameLoggedIn = User::isLoggedIn() ? $_SESSION['userLoggedIn']  : "";
$userLoggedInObj = new User($con, $usernameLoggedIn);

$buttonProovider = new ButtonProvider();
$navigationMenu = new NavigarionMenuProvider($con,$userLoggedInObj);

 ?>
<!DOCTYPE html>
<html lang="en" dir='rtl'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Video Tube</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/rtl.css">
</head>
<body>
 
    <div id="pageContainer">
         <div id="mastHeadContainer">
            <button class="navShowHide"><i class="fa fa-bars"></i></button>
            <a href="index.php" class="logoContainer">
                <img src="assets/images/icons/VideoTubeLogo.png" alt="logo" title="logo">
            </a>
            <div class="searchBarContainer">
                <form action="search.php" method="get">
                    <input type="text" name="term" class="searchBar" placeholder="بحث">
                    <button class="searchButton">
                    <img src="assets/images/icons/search.png" alt="search" title="search">
                    </button>
                </form>
            </div>
            <div class="rightIcons">
                <a href="upload.php">
                    <img src="assets/images/icons/upload.png" alt="upload" title="تحميل">  
                </a>
                <span class="username hidden">

                   
                    <?php

                    echo $userLoggedInObj->getUserName();
                      $pagebase =  basename($_SERVER['PHP_SELF']);
                      $implode = explode('.',$pagebase);
                      $pageName = $implode[0];

                     
                    
                    ?>
                </span>
               
            </div>
         </div>

         <div id="sideNavContainer">

         <?php echo $navigationMenu->create($pageName); ?>
           
         </div>
         <div id="mainSectionContainer" class="leftPadding">
<?php 
require_once "../includes/config.php";
require_once "../includes/classes/User.php";
require_once "../includes/classes/Comment.php";

if(isset($_POST['commentText']) && isset($_POST['postedBy']) && isset($_POST['videoId'])) {

    $postedBy        = $_POST['postedBy'];
    $videoId         = $_POST['videoId'];
    if($_POST['responseTo'] != '') {
        $responseTo  = $_POST['responseTo'];
     }
     else {
          $responseTo   = 0;
     }
    $commentText     = $_POST['commentText'];
    $userLoggedInObj = new User($con,$_SESSION['userLoggedIn']);
    
    // query to insert the comment into the comments table 

    $query = $con->prepare("INSERT INTO comments(postedBy,videoId,responseTo,body) VALUES(:postedBy,:videoId,:responseTo,:body)");

    $query->bindValue(":postedBy",$postedBy);
    $query->bindValue(":videoId",$videoId);
    $query->bindValue(":responseTo",$responseTo);
    $query->bindValue(":body",$commentText);
    $query->execute();
    $lastCommentId = $con->lastInsertId();
    
    $newComment = new Comment($con,$lastCommentId,$userLoggedInObj,$videoId);
    echo $newComment->create();

}

// if(isset($_POST['getAllComments']) && $_POST['videoId'] != null){

//     $videoId = $_POST['videoId'];
//     echo "correct request to $videoId";
// }
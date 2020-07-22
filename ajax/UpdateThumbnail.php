<?php 
require_once "../includes/config.php";


if(isset($_POST['thumbnailId']) && isset($_POST['videoId']) ) {

    $videoId = $_POST['videoId'];
    $thumbnailId = $_POST['thumbnailId'];

   // set all the thumbnail selected value to 0
    $query = $con->prepare("UPDATE thumbnails SET selected = 0 WHERE videoId = :videoId ");

    $query->bindValue(":videoId",$videoId);

    $query->execute();


    // update the selected one to 1 

    $query = $con->prepare("UPDATE thumbnails SET selected = 1 WHERE id = :id ");

    $query->bindValue(":id",$thumbnailId);

    $query->execute();




} else {

}
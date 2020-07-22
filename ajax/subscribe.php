<?php 

require_once "../includes/config.php";


if(isset($_POST['subscribeTo']) && isset($_POST['loggedInUser'])) {

    $subscribeTo = $_POST['subscribeTo'];
    $subscribeFrom = $_POST['loggedInUser'];
   

    // check if the user already subscribe 

    $query = $con->prepare("SELECT * FROM subscribers WHERE subscribTo = :subscribTo AND subscribFrom = :subscribFrom");

    $query->bindValue(":subscribTo",$subscribeTo);
    $query->bindValue(":subscribFrom",$subscribeFrom);
 
    $query->execute();


    // if subed delete the subscribton 

    if($query->rowCount() > 0) {

        $query = $con->prepare("DELETE FROM subscribers WHERE subscribTo = :subscribTo AND subscribFrom = :subscribFrom");

        $query->bindValue(":subscribTo",$subscribeTo);
        $query->bindValue(":subscribFrom",$subscribeFrom);
    
        $query->execute();

    } else {
            // if not subed insert 

            $query = $con->prepare("INSERT INTO  subscribers (subscribTo,subscribFrom) VALUES (:subscribTo,:subscribFrom)");

            $query->bindValue(":subscribTo",$subscribeTo);
            $query->bindValue(":subscribFrom",$subscribeFrom);
        
            $query->execute();
    }

      $query = $con->prepare("SELECT * FROM subscribers WHERE subscribTo = :subscribTo");

      $query->bindValue(":subscribTo",$subscribeTo);
  
      $query->execute();
     echo $query->rowCount();
 

  

    // return new number of subs
}


if(isset($_POST['getAllSubscribers']) && isset($_POST['subscriberTo'])) {

    $subscribeTo = trim($_POST['subscriberTo']);
    $query = $con->prepare("SELECT * FROM subscribers WHERE subscribTo = :subscribTo");

    $query->bindValue(":subscribTo",$subscribeTo);

    $query->execute();
   echo $query->rowCount();

}

if(isset($_SESSION['userLoggedIn']) && isset($_POST['check'])) {

    
    $issubscriberTo = trim($_POST['issubscriberTo']);
    $subscribeFrom = trim($_POST['subscribeFrom']);
        // check if the user already subscribe 
 
        $query = $con->prepare("SELECT * FROM subscribers WHERE subscribTo = :subscribTo AND subscribFrom = :subscribFrom");

        // $query = $con->prepare("SELECT * FROM users");

        $query->bindValue(":subscribTo",$issubscriberTo);
        $query->bindValue(":subscribFrom",$subscribeFrom);
     
         $query->execute();

         $result = $query->fetch(PDO::FETCH_ASSOC);

        $isSubscribed = $query->rowCount();



       // return the count of all subscribers

        $query = $con->prepare("SELECT * FROM subscribers WHERE subscribTo = :subscribTo");
    
        $query->bindValue(":subscribTo",$issubscriberTo);
    
        $query->execute();

        $subCount = $query->rowCount();


        $data = array(
           "isSubscribed" => $isSubscribed,
           "subCount"     =>  $subCount

        );


     print_r (json_encode($data));

     
}
<?php

class SubscriptionsProvider {

    private $con,$userLoggedInObj;
    public function __construct($con,$userLoggedInObj) {

        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

    }

   // method to get the videos of the people the signed user subscribe to them 

    public function getVideos() {

      $videos = array();

      $subscribtions = $this->userLoggedInObj->getSubscriptions();

      $condition = "";
      

      if(count($subscribtions) > 0) {

          

        for ($i= 0; $i < count($subscribtions) ; $i++) {
            

           if($i == 0) {
               $condition = "WHERE uploadedBy = ?";
             
           } else {
               $condition .= " OR uploadedBy = ?";
             
           }

          
        }


        $videoSql = "SELECT * FROM videos $condition ORDER BY uploadDate DESC";

        $videoQuery = $this->con->prepare($videoSql);

        $i = 1;

        foreach($subscribtions as $subs) {
            $videoQuery->bindValue($i,$subs);
            $i++;
        }

        if($videoQuery->execute()) {
            

            while($row = $videoQuery->fetch(PDO::FETCH_ASSOC)) {

                $video = new Video($this->con,$row,$this->userLoggedInObj);
                
                array_push($videos,$video);

            }
        }
      }


       return $videos;
    }

}
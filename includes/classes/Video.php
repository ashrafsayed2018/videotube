<?php 
class Video {
    private $con, $sqlData, $userLoggedInObj;

    public function __construct($con,$input,$userLoggedInObj) {
        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

        if(is_array($input)) {
            $this->sqlData = $input;
        }
        else {
            $query = $this->con->prepare("SELECT * FROM videos WHERE id = :id");

            $query->bindValue(":id",$input);
            $query->execute();
        
            $count = $query->rowCount();

            if($count > 0) {

                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            } else {

                $query = $this->con->prepare("SELECT * FROM videos WHERE id = :id");

                $query->bindValue(":id",1);
                $query->execute();
                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
        }
     



    }

    // get the video id 

    public function getVideoId() {
        return $this->sqlData['id'];
    }

    // get the uploadedBy 

    public function getUploadedBy() {
        return $this->sqlData['uploadedBy'];
    }

    // get the video title

     public function getTitle() { 
        return $this->sqlData['title'];

     }

    // get the video description 

    public function getDescription() {
        return $this->sqlData['description'];
    }

    // get the video privacy 

    public function getPrivacy() {
        return $this->sqlData['privacy'];
    }

    // get video category

    public function getCategory() {
        return $this->sqlData['category'];
    }

    // get video filepath

    public function getFilePath() {
        return $this->sqlData['filePath'];
    }

    // get video upload date

    public function getUploadDate() {
       $date =  $this->sqlData['uploadDate'];

       return date("M j, Y",strtotime($date));
    }

        // get video upload date

        public function timeStamp() {
            $date =  $this->sqlData['uploadDate'];
     
            return date("M jS, Y",strtotime($date));
         }

    // get video views

    public function getViews() {
        return $this->sqlData['views'];
    }
    // get video duration

    public function getDuration() {
        return $this->sqlData['duration'];
    }

    // increment view 

    public function incrementViews() {

        $query = $this->con->prepare("UPDATE videos SET views = views + 1 WHERE id = :id");

        $videoId = $this->getVideoId();

        $query->bindValue(":id",$videoId);
        $query->execute();

        $this->sqlData['views'] = $this->sqlData['views'] + 1;
    }

    public function getLikes() {
        $query = $this->con->prepare("SELECT COUNT(*) AS 'count' FROM likes  WHERE videoId = :id");
        $videoId = $this->getVideoId();
        $query->bindValue(":id",$videoId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_OBJ);
        return $data->count;
    }

    public function getDisLikes() {
        $query = $this->con->prepare("SELECT COUNT(*) AS 'count' FROM dislikes  WHERE videoId = :id");
        $videoId = $this->getVideoId();
        $query->bindValue(":id",$videoId);
        $query->execute();

        $data = $query->fetch(PDO::FETCH_OBJ);
        return $data->count;
    }

    // method to like the video 

    public function like() {

        $id = $this->getVideoId();

        $username =  $_SESSION['userLoggedIn'];
        // check if that video is already liked by the username

        if($this->wasLikedBy()) {

            // user has already like the video
            
            $query = $this->con->prepare("DELETE FROM likes WHERE username = :un AND videoId = :videoId");
            $query->bindValue(":un",$username);
            $query->bindValue(":videoId",$id);
            $query->execute();

            
            // return the result array 

            $result = array(
                'likes' => -1,
                "dislikes" => 0
            );

            return json_encode($result);
           
           
        } else {
          
             // delete the dislike on that video if the user already dislike it

             $query = $this->con->prepare("DELETE FROM dislikes WHERE username = :un AND videoId = :videoId");
             $query->bindValue(":un",$this->userLoggedInObj);
             $query->bindValue(":videoId",$id);
             $query->execute();
             $count = $query->rowCount();


             // user not like the video yet
                  $query = $this->con->prepare("INSERT INTO likes (username,videoId) VALUE(:un,:videoId)");
                  $query->bindValue(":un",$this->userLoggedInObj);
                  $query->bindValue(":videoId",$id);
                  $query->execute();
               // return the result array 

            $result = array(
                'likes' => 1,
                "dislikes" => 0 - $count
            );

            return json_encode($result);
          
           
        }
       
    }


    // check if video was liked by the user 

    public function wasLikedBy() {
          // check if that video is already liked by the username

          $id = $this->getVideoId();

          if(isset($_SESSION['userLoggedIn'])) {
            $username = $_SESSION['userLoggedIn'];

            $query = $this->con->prepare("SELECT * FROM likes WHERE videoId = :id AND username = :un");
    
            $query->bindValue(":id",$id);
            $query->bindValue(":un",$username);
     
            $query->execute();
    
            return $query->rowCount() > 0;
          } else {
              return false;
          }

        
    }


        // method to dislike the video 

        public function disLike() {

            $id = $this->getVideoId();
    
          $username =  $_SESSION['userLoggedIn'];;
         
            // check if that video is already dis liked by the username
    
            if($this->wasDisLikedBy()) {
    
                // user has already dislike the video
                
                $query = $this->con->prepare("DELETE FROM dislikes WHERE username = :un AND videoId = :videoId");
                $query->bindValue(":un",$username);
                $query->bindValue(":videoId",$id);
                $query->execute();
    
                
                // return the result array 
    
                $result = array(
                    'likes' => 0,
                    "dislikes" => -1
                );
    
                return json_encode($result);
               
               
            } else {
    
                // user not dislike the video yet
            
                
                 // delete the like on that video if the user already like it
    
                 $query = $this->con->prepare("DELETE FROM likes WHERE username = :un AND videoId = :videoId");
                 $query->bindValue(":un",$this->userLoggedInObj);
                 $query->bindValue(":videoId",$id);
                 $query->execute();

                 $count = $query->rowCount();

                 $query = $this->con->prepare("INSERT INTO dislikes (username,videoId) VALUE(:un,:videoId)");
                 $query->bindValue(":un",$this->userLoggedInObj);
                 $query->bindValue(":videoId",$id);
                 $query->execute();
    
                   // return the result array 
    
                $result = array(
                    'likes' => 0 - $count, 
                    "dislikes" => 1
                );
    
                return json_encode($result);
              
               
            }
           
        }
    

    // check if video was disliked by the user 

    public function wasDisLikedBy() {
          // check if that video is already liked by the username

          $id = $this->getVideoId();

          if(isset($_SESSION['userLoggedIn'])) {

          $username = $_SESSION['userLoggedIn'];

          $query = $this->con->prepare("SELECT * FROM dislikes WHERE videoId = :id AND username = :un");
  
          $query->bindValue(":id",$id);
          $query->bindValue(":un",$username);
  
          $query->execute();
  
          return $query->rowCount() > 0;
          }
          else {
              return false;
          }
    }

    public function getNumberOfComments() {

        $id = $this->getVideoId();
        
        $query = $this->con->prepare("SELECT * FROM comments WHERE videoId = :id ");

        $query->bindValue(":id",$id);

        $query->execute();

        return $query->rowCount();
    }

    // method to get all the comment on the video 

    public function getAllComments() {

        $id = $this->getVideoId();
        
        $query = $this->con->prepare("SELECT * FROM comments WHERE videoId = :id AND responseTo = 0 ORDER BY datePosted DESC ");

        $query->bindValue(":id",$id);

        $query->execute();

        $comments = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {

            $comment = new Comment($this->con,$row,$this->userLoggedInObj,$id);
           array_push($comments,$comment);
        }

        return $comments;

     }

    //  method to get video thumbnail

    public function getThumbnail() {

        $videoId = $this->getVideoId();

        $query = $this->con->prepare("SELECT filePath FROM thumbnails WHERE videoId = :videoId AND selected = 1");
        
        $query->bindParam(":videoId",$videoId);

        $query->execute();

        $count = $query->rowCount();

        if($count > 0 ) {
            return  $query->fetchColumn();
        } else  {

            $query = $this->con->prepare("SELECT filePath FROM  videos WHERE id = :videoId ");
            $query->bindParam(":videoId",$videoId);
    
            $query->execute();

          $data = $query->fetchColumn();

            return $data;

        }
      
    }
}



<?php 
require_once "ButtonProvider.php";
require_once "CommentControls.php";
class Comment {
    
    private $con,$sqlData,$userLoggedInObj,$videoId;
    public function __construct($con,$input,$userLoggedInObj,$videoId) {

        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;
        $this->videoId = $videoId;

        if(!is_array($input)) {
          
            $query = $this->con->prepare("SELECT * FROM comments WHERE id = :id");
            $query->bindValue(":id",$input);
            $query->execute();
        
            $input = $query->fetch(PDO::FETCH_ASSOC);
        }

        $this->sqlData = $input;

    } 

    public function create() {
        $commentId  = $this->sqlData['id'];
        $videoId    = $this->getVideoId();
        $body       = $this->sqlData['body'];
        $postedBy   = $this->sqlData['postedBy'];
        $datePosted = $this->sqlData['datePosted'];

        $datePosted     = $this->time_elapsed_string($this->sqlData['datePosted']);
        $profileButton  = ButtonProvider::createUserProfileButton($this->con,$postedBy);

        $commentControlsObj = new CommentControls($this->con,$this,$this->userLoggedInObj);
        $commentControls    = $commentControlsObj->create();

        $numResponses = $this->getNumberOfReplies();
   
        if($numResponses > 0) {
            
            if($numResponses == 1) {
                $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies(this,$commentId,$videoId)'>   عرض  رد واحد </span>";
            } else {
                $viewRepliesText = "<span class='repliesSection viewReplies' onclick='getReplies(this,$commentId,$videoId)'> عرض $numResponses  ردود </span>"; 
            }
        } else {
            $viewRepliesText = "<div class='repliesSection'> </div>";
        }

        return "<div class='itemContainer'>
                    <div class='comment'>
                        $profileButton
                        <div class='mainContainer'>

                             <div class='commentHeader'>
                               
                                     <a href='profile.php?username=$postedBy'>
                                        <span class='postedBy'>
                                            $postedBy 
                                        </span>
                                     </a>
                                
                                 <span class='timestamp'>  $datePosted</span>
                               
                             </div>

                             <div class='body'>
                                $body
                             </div>

                        </div>
                      
                    </div>
                    $commentControls
                    $viewRepliesText
                </div>";
             
    }

    public function getCommentId() {
        return $commentId = $this->sqlData['id'];
    }

    public function getVideoId() {
        return $this->videoId;
    }

    public function getLikes() {


        $commentId = $this->getCommentId();
        // count the comment likes 

        $query = $this->con->prepare("SELECT COUNT(*) AS 'count' FROM likes WHERE commentId = :commentId");

        $query->bindValue(":commentId",$commentId);

        $query->execute();

        $data = $query->fetch(PDO::FETCH_OBJ);

        $numLikes = $data->count;


        // count the comment dislikes 

        $query = $this->con->prepare("SELECT COUNT(*) AS 'count' FROM dislikes WHERE commentId = :commentId");

        $query->bindValue(":commentId",$commentId);

        $query->execute();

        $data = $query->fetch(PDO::FETCH_OBJ);

        $numDisLikes = $data->count;

        return $numLikes - $numDisLikes;
    }

    // method to like the comment 

       public function like() {

        $id = $this->getCommentId();

        $username =  $_SESSION['userLoggedIn'];
        // check if that video is already liked by the username

        if($this->wasLikedBy()) {

            // user has already like the video
            
            $query = $this->con->prepare("DELETE FROM likes WHERE username = :un AND commentId = :commentId");
            $query->bindValue(":un",$username);
            $query->bindValue(":commentId",$id);
            $query->execute();

            
            // return the result array 

            return -1;
           
           
        } else {
          
             // delete the dislike on that comment if the user already dislike it

             $query = $this->con->prepare("DELETE FROM dislikes WHERE username = :un AND commentId = :commentId");
             $query->bindValue(":un",$username);
             $query->bindValue(":commentId",$id);
             $query->execute();
             $count = $query->rowCount();


             // user not like the comment yet
                  $query = $this->con->prepare("INSERT INTO likes (username,commentId) VALUE(:un,:commentId)");
                  $query->bindValue(":un",$username);
                  $query->bindValue(":commentId",$id);
                  $query->execute();
               // return the result array 

            return 1 + $count;
          
           
        }
       
    }

    // check if comment  was liked by the user 

    public function wasLikedBy() {
            // check if that video is already liked by the username
  
            $id = $this->getCommentId();
  
            if(isset($_SESSION['userLoggedIn'])) {
              $username = $_SESSION['userLoggedIn'];
  
              $query = $this->con->prepare("SELECT * FROM likes WHERE commentId = :id AND username = :un");
      
              $query->bindValue(":id",$id);
              $query->bindValue(":un",$username);
      
              $query->execute();
      
              return $query->rowCount() > 0;
            } else {
                return false;
            }
    }

    // method to dislike the comment 

    public function disLike() {

                $id = $this->getCommentId();
        
              $username =  $_SESSION['userLoggedIn'];
             
                // check if that coment is already dis liked by the username
        
                if($this->wasDisLikedBy()) {
        
                    // user has already dislike the comment
                    
                    $query = $this->con->prepare("DELETE FROM dislikes WHERE username = :un AND commentId = :commentId");
                    $query->bindValue(":un",$username);
                    $query->bindValue(":commentId",$id);
                    $query->execute();
        
                
                    return 1;
                   
                   
                } else {
        
                    // user not dislike the comment yet
                
                    
                     // delete the like on that comment if the user already like it
        
                     $query = $this->con->prepare("DELETE FROM likes WHERE username = :un AND commentId = :commentId");
                     $query->bindValue(":un",$username);
                     $query->bindValue(":commentId",$id);
                     $query->execute();
    
                     $count = $query->rowCount();
    
                     $query = $this->con->prepare("INSERT INTO dislikes (username,commentId) VALUE(:un,:commentId)");
                     $query->bindValue(":un",$username);
                     $query->bindValue(":commentId",$id);
                     $query->execute();
        

        
                    return -1 - $count;
                  
                   
                }
               
    }
        
    // check if comment was disliked by the user 

    public function wasDisLikedBy() {
        // check if that video is already liked by the username

        $id = $this->getCommentId();

        if(isset($_SESSION['userLoggedIn'])) {

        $username = $_SESSION['userLoggedIn'];

        $query = $this->con->prepare("SELECT * FROM dislikes WHERE commentId = :id AND username = :un");

        $query->bindValue(":id",$id);
        $query->bindValue(":un",$username);

        $query->execute();

        return $query->rowCount() > 0;
        }
        else {
            return false;
        }
    }

    // function get get the time as time age

    public function time_elapsed_string($datetime, $full = false) {
      $now = new DateTime;
      $ago = new DateTime($datetime);
      $diff = $now->diff($ago);
  
      $diff->w = floor($diff->d / 7);
      $diff->d -= $diff->w * 7;
  
      $string = array(
          'y' => 'سنه',
          'm' => 'شهر',
          'w' => 'اسبوع',
          'd' => 'يوم',
          'h' => 'ساعه',
          'i' => 'دقيقه',
          's' => 'ثانيه',
      );
      foreach ($string as $k => &$v) {
          if ($diff->$k) {
              $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
          } else {
              unset($string[$k]);
          }
      }
  
      if (!$full) $string = array_slice($string, 0, 1);
      return $string ?  ' منذ ' . implode(', ', $string) : 'الان';
    }

    // method to get number of replies on the specific comment 

    public function getNumberOfReplies() {


        $commentId = $this->sqlData['id'];
        $query = $this->con->prepare("SELECT count(*) FROM comments WHERE responseTo =:responseTo");

        $query->bindValue(":responseTo",$commentId);
        $query->execute();

        return $query->fetchColumn();
  
    }


    // get the comment replies 

    public function getReplies() {

        $id = $this->getCommentId();
        
        $query = $this->con->prepare("SELECT * FROM comments WHERE responseTo = :commentId  ORDER BY datePosted ASC ");

        $query->bindValue(":commentId",$id);

        $query->execute();

        $comments = '';
        $videoId = $this->getVideoId();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {

          $comment = new Comment($this->con,$row,$this->userLoggedInObj,$videoId);
           $comments .= $comment->create();
        }

        return $comments;
    }


}
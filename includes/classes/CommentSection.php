<?php 

class CommentSection{

    public $con, $video, $userLoggedInObj;

    public function __construct($con,$video,$userLoggedInObj) {
        $this->con = $con;
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {

       return  $this->createCommentSection();

    }

    private function createCommentSection() {
      
        $numComments =  $this->video->getNumberOfComments();
        $postedBy    = isset($_SESSION['userLoggedIn']) ? $_SESSION['userLoggedIn'] : "";
        $videoId     = $this->video->getVideoId();

        if(isset($_SESSION['userLoggedIn'])) {
        $profilePic     = ButtonProvider::createUserProfileButton($this->con, $postedBy);
        } else {
            $profilePic = "<img src='assets/images/profilePictures/default.png' class='profilePic'>";
        }
        $commentAction = "postComment(this,\"$postedBy\",$videoId,null,\"comments\")";

        if(isset($_SESSION['userLoggedIn'])) {
        $commentButton = ButtonProvider::createButton("تعليق",null,$commentAction,'postComment');

        } else {
            $commentButton = ButtonProvider::createButton("تعليق",null,'window.location.href="signin.php"','postComment'); 
        }

        $comments = $this->video->getAllComments();
        $commentItems ='';

        foreach ($comments as $comment) {

            $commentItems .= $comment->create();
        }

       

        // get comments html 

        echo  "<div class='commentSection'>

                    <div class='header'>

                        <span class='commemtsCount'> 
                            $numComments تعليقات
                        </span>
                        
                        <div class='commentForm'>
                            $profilePic
                            <textarea class='commentBody' placeholder='اضافة تعليق عام '></textarea>
                            $commentButton
                        </div>
                    
                    </div>

                    <div class='comments'> $commentItems</div>

                </div>";
    }

}
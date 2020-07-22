<?php
require_once "ButtonProvider.php";

class CommentControls {

    private  $con, $comment, $userLoggedInObj;

    public function __construct($con, $comment,$userLoggedInObj) {
        $this->con = $con;
        $this->comment = $comment;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {

        $replyButton   = $this->createReplyButton();
        $likesCount    = $this->createLikesCount();
        $likeButton    = $this->createLikeButton();
        $dislikeButton = $this->createDisLikeButton();
        $replySection  = $this->createReplySection();
       
        return "<div class='controls'>
                  $replyButton
                  $likeButton
                  $dislikeButton
                  $likesCount
                </div>
              $replySection
                ";
    }

    private function createReplyButton() {
         
        $text = "رد";
        $action = "toggleReply(this)";

        return ButtonProvider::createButton($text,null,$action,'reply');
    }
    
    private function createLikesCount() {
        
        $text = $this->comment->getLikes();

        if($text == 0) { $text = ""; }

        return "<span class='likesCount'>$text</span>";
    }

    private function createLikeButton() {

        $commentId = $this->comment->getCommentId();
        $videoId   =  $this->comment->getVideoId();
        $imageSrc  = "assets/images/icons/thumb-up.png";
        $action    = "likeComment(this,$videoId,$commentId)";
        $class     = "likeButton";

        if($this->comment->wasLikedBy()) {
            $imageSrc = "assets/images/icons/thumb-up-active.png";
        }
        // change the image if the button get liked
        if(isset($_SESSION['userLoggedIn'])) {
        return ButtonProvider::createButton("",$imageSrc,$action,$class);
        }
        else {
            return ButtonProvider::createButton("",$imageSrc,'window.location.href="signin.php"',$class);   
        }

 
    }

    private function createDisLikeButton() {

        $commentId = $this->comment->getCommentId();
        $videoId   =  $this->comment->getVideoId();
        $imageSrc  = "assets/images/icons/thumb-down.png";
        $action    = "dislikeComment(this,$videoId,$commentId)";
        $class     = "disLikeButton";
        // change the image if the button get liked

        if($this->comment->wasDisLikedBy()) {
            $imageSrc = "assets/images/icons/thumb-down-active.png";
        }
        if(isset($_SESSION['userLoggedIn'])) {
            return ButtonProvider::createButton("",$imageSrc,$action,$class);
        } else {
            return ButtonProvider::createButton("",$imageSrc,'window.location.href="signin.php"',$class); 
        }
     

    }

    private function createReplySection(){

        $postedBy    = isset($_SESSION['userLoggedIn']) ? $_SESSION['userLoggedIn'] : "";
        $commentId = $this->comment->getCommentId();
        $videoId     = $this->comment->getVideoId();

        $profilePic     = ButtonProvider::createUserProfileButton($this->con, $postedBy);

        $cancelButtonAction = "toggleReply(this)";
        $cancelButton = ButtonProvider::createButton("الغاء",null,$cancelButtonAction,'cancelComment');

        $postButtonAction = "postComment(this, \"$postedBy\",$videoId,$commentId,\"repliesSection\")";

        if(isset($_SESSION['userLoggedIn'])) {
        $postButton = ButtonProvider::createButton("رد",null,$postButtonAction,'postComment');
        } else {
            $postButton = ButtonProvider::createButton("رد",null,'window.location.href="signin.php"','postComment'); 
        }


        return  "<div class='commentForm hidden'>
                    $profilePic
                    <textarea class='commentBody' placeholder='Add a Public Comment'></textarea>
                    $postButton
                    $cancelButton
              </div>";

    }
}

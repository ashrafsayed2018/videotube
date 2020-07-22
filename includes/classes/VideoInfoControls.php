<?php

require_once "includes/classes/ButtonProvider.php";

class VideoInfoControls{

    private  $video, $userLoggedInObj;

    public function __construct($video,$userLoggedInObj) {
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {

        $likeButton = $this->createLikeButton();
        $dislikeButton = $this->createDisLikeButton();
       
        return "<div class='controls'>
                  $likeButton
                  $dislikeButton
                </div>";
    }

    private function createLikeButton() {

        $text = $this->video->getLikes();
        $videoId = $this->video->getVideoId();
        $imageSrc = "assets/images/icons/thumb-up.png";
        $action = "likeVideo(this,$videoId)";
        $class = "likeButton";

        if($this->video->wasLikedBy()) {
            $imageSrc = "assets/images/icons/thumb-up-active.png";
        }
        // change the image if the button get liked
        if(isset($_SESSION['userLoggedIn'])) {
        return ButtonProvider::createButton($text,$imageSrc,$action,$class);
        }
        else {
            return ButtonProvider::createButton($text,$imageSrc,'window.location.href="signin.php"',$class);   
        }

 
    }

    private function createDisLikeButton() {

        $text = $this->video->getDisLikes();
        $videoId = $this->video->getVideoId();
        $imageSrc = "assets/images/icons/thumb-down.png";
        $action = "dislikeVideo(this,$videoId)";
        $class = "disLikeButton";
        // change the image if the button get liked

        if($this->video->wasDisLikedBy()) {
            $imageSrc = "assets/images/icons/thumb-down-active.png";
        }
        if(isset($_SESSION['userLoggedIn'])) {
            return ButtonProvider::createButton($text,$imageSrc,$action,$class);
        } else {
            return ButtonProvider::createButton($text,$imageSrc,'window.location.href="signin.php"',$class); 
        }
     

    }
}
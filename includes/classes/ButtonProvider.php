<?php 

class ButtonProvider {

    public static function createButton($text,$imageSrc,$action,$class) {

        $image = $imageSrc == null ? '' : "<img src='$imageSrc'>";

        // change action if needed 

       return  "<button class='$class' onclick='$action' > 
                   $image
                  <span class='text'>$text</span>
               </button>";
    }

    // create user profile button

    public static function createUserProfileButton($con,$username) {
        $userObj = new User($con,$username);
        $profilePic = $userObj->getUserProfilePic();
        $link =  "profile.php?username=$username";

        return "<a href='$link'>
                   <img src='$profilePic' class='profilePic'>
                </a>";
    }

    // create edit video button 

    public static function createEditVideoButton($videoId) {
        
        $href = "editVideo.php?videoId=$videoId";
        $button = ButtonProvider::createHyperLinkButton("EDIT VIDEO",NULL,$href,'edit button');

        return "<div class='editVideoContainer'>
                $button
                </div>";
    }

    // create heyper link button 

    public static function createHyperLinkButton($text,$imageSrc,$href,$class) {

        $image = $imageSrc == null ? '' : "<img src='$imageSrc'>";

        // change action if needed 

       return  "<a href='$href'>
                  <button class='$class' > 
                    $image
                    <span class='text'>$text</span>
                  </button>
                </a>";
    }

    // create subscription button 

    public static function createSubcriberButton($con,$subseibeToObj,$userLoggedInObj) {

        $subscibeTo =  $subseibeToObj->getUserName(); 
        $userLoggedIn = $userLoggedInObj->getUserName();

        // check if the logged in user is subscribe to the video owner 

        $isSubscribeTo = $userLoggedInObj->isSubscribedTo($userLoggedInObj->getUserName());

        $buttonText = $isSubscribeTo ? "SUBSCRIBED" : "SUBSCRIBE";

        $buttonText .= " " . $subseibeToObj->getSubscribersCount();

        $buttonClass = $isSubscribeTo ? "unsubscribe button" : "subscribe button";

        $action = "subscribe(this,\"$subscibeTo\",\"$userLoggedIn\")";

        if(isset($_SESSION['userLoggedIn'])) {

        $button = ButtonProvider::createButton($buttonText,null,$action,$buttonClass);
        } else {
            $button = ButtonProvider::createButton($buttonText,null,'window.location.href="signin.php"',$buttonClass);
        }

        return "<div class='subscribeButtonContainer'>
        $button
                   $isSubscribeTo
                </div>";

     

    
    }

    // create user profile navigation button 

    public function createUserProfileNavigatinButton($con,$userLoggedInObj) {

        if(!User::isLoggedIn()) {
            return " <a href='signin.php'>
                         <span class='signInLink'>SIGN IN</span>
                       </a>";
        } else {
            return ButtonProvider::createUserProfileButton($con,$userLoggedInObj->getUserName());
        }
    }
}
?>

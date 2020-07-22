<?php 

class NavigarionMenuProvider {

    private $con,$userLoggedInObj;
    public function __construct($con,$userLoggedInObj) { 

        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

    }


    public function create($pagename) {

        
        $menuHtml  = $this->createNavItems("الرئيسيه",'assets/images/icons/home.png','index.php',$pagename == "index" ? "selected": "");
        $menuHtml .= $this->createNavItems("المحتوي الرائج",'assets/images/icons/trending.png','trending.php',$pagename == "trending" ? "selected": "");
        $menuHtml .= $this->createNavItems("الاشتراكات",'assets/images/icons/subscriptions.png','subscriptions.php',$pagename == "subscriptions" ? "selected": "");
        $menuHtml .= $this->createNavItems("فيديوهات اعجبتك",'assets/images/icons/thumb-up.png','likedVideos.php',$pagename == "likedVideos" ? "selected": "");

        if(User::isLoggedIn()) {

            $username = $this->userLoggedInObj->getUserName();
            $menuHtml .= $this->createNavItems("قناتي","assets\images\profilePictures\default.png","profile.php?username=$username",$pagename == "profile" ? "selected": "");
            $menuHtml .= $this->createNavItems("الاعدادات",'assets/images/icons/settings.png','settings.php',$pagename == "settings" ? "selected": "");
            $menuHtml .= $this->createNavItems("خروج",'assets/images/icons/logout.png','logout.php',$pagename == "logout" ? "selected": "");
            
            // create Subscriptios section

            $menuHtml .= $this->createSubscriptionSection();

        }

    

        return "<div class='navigationItems'>
                     $menuHtml
               </div>";
        
    

    }

    private function createNavItems($text,$icon,$link,$class) {

        return "<div class='navigatinItem $class'>
                    <a href='$link'>
                         <img src='$icon'>
                         <span> $text</span>
                     </a>
               </div>";
    }

    private function createSubscriptionSection() {

        $subscription = $this->userLoggedInObj->getSubscriptions();


        $html = "<span class='heading'>الاشتراكات</span>";

        foreach($subscription as $sub) {

            $query =$this->con->prepare("SELECT profilePic FROM users WHERE username = '$sub'");

            $query->execute();

           while($row = $query->fetch(PDO::FETCH_ASSOC))  {
                $picture = $row['profilePic'];
           }


            // $userPic     = $sub->getUserProfilePic();
            
             $html .= $this->createNavItems($sub,$picture,"profile.php?username=".$sub,"sub");

           
        }

         return $html;
    

    }

}
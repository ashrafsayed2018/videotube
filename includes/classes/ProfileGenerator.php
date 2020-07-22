<?php 
require_once "ProfileData.php";
class ProfileGenerator {

    private $con, $userLoggedInObj, $profileData;

    public function __construct($con, $userLoggedInObj, $profileUsername) {

        $this->con              = $con;
        $this->userLoggedInObj  = $userLoggedInObj;
        $this->profileData      = new ProfileData($this->con,$profileUsername);

    }

    public function create() {
        
     $profileUsername = $this->profileData->getProfielUsername();
      if(!$this->profileData->userExists()) {
        return "<span class='errorMessage'> User is not Exists </span>";
      }

      $coverPhotoSection = $this->createCoverPhotoSection();
      $headerSection     = $this->createHeaderSection();
      $tabsection        = $this->createTabsSection();
      $contentSection    = $this->createContentSection();

      return "<div class='profileContainer'>
                $coverPhotoSection
                $headerSection
                $tabsection
                $contentSection
              </div>
            ";

    }


    private function createCoverPhotoSection() {
          
        $coverPhotoSrc = $this->profileData->getCoverPhoto();
        $name = $this->profileData->getProfileUserFullname();

        return "<div class='coverPhotoContainer'>
                    <img src='$coverPhotoSrc' class='coverPhot'>
                    <span class='chanelname'> $name </span>
                </div>";

    }

    private function createHeaderSection() {

       $profileImage = $this->profileData->getProfilePic();
       $name         = $this->profileData->getProfileUserFullname();
       $subCount     = $this->profileData->getSubscribersCount();
       $username     = $this->profileData->getUserName();
       $button       = $this->createHeaderButton();

       return "<div class='profileHeader'>
                     <div class='userInfoConteiner'>
                          <img class='profileImage' src='$profileImage'> 
                           <div class='userInfo'>
                               <span class='title'> $name </span>
                               <span class='author hidden'>
                              <a href='#'>
                                 $username
                              </a>
                           </span>
                               <span class='subsriberCount'> $subCount مشتركين</span>
                           </div>                   
                      </div>
                     <div class='buttonConteiner'>
                        $button
                     </div>
               </div>
              
               ";
               echo $profileImage;

    }



     public function createTabsSection() {
        return "<ul class='nav nav-tabs' role='tablist'>
                    <li class='nav-item'>
                    <a class='nav-link active' id='videos-tab' data-toggle='tab' 
                        href='#videos' role='tab' aria-controls='videos' aria-selected='true'>الفيديوهات</a>
                    </li>
                    <li class='nav-item'>
                    <a class='nav-link' id='about-tab' data-toggle='tab' href='#about' role='tab' 
                        aria-controls='about' aria-selected='false'>جول المستخدم</a>
                    </li>
                </ul>";
    }

    private function createContentSection() {

        $videos = $this->profileData->getUserVideos();



        if(sizeof($videos)) {
            $videoGrid = new VideoGrid($this->con,$this->userLoggedInObj);
            $videoGridHtml = $videoGrid->create($videos,null,false);
        } else {
            $videoGridHtml = "<span> this  user has no videos </span>";
        }

        $aboutSecton = $this->createAboutSection();

        return "<div class='tab-content channelContent'>
                    <div class='tab-pane fade show active in' id='videos' role='tabpanel' aria-labelledby='videos-tab'>
                        $videoGridHtml
                    </div>
                    <div class='tab-pane fade' id='about' role='tabpanel' aria-labelledby='about-tab'>
                        $aboutSecton
                    </div>
                </div>";
    }

    private function createHeaderButton() {

         
        if($this->userLoggedInObj->getUserName() == $this->profileData->getProfielUsername()) {
            return "";
        } else {
            return ButtonProvider::createSubcriberButton($this->con,$this->profileData,$this->userLoggedInObj);
        }
    }


    private function createAboutSection() {

        $details = $this->profileData->getAllUserDetails();
        $html = "<div class='section'>
        <div class='title'>
             <span>التفاصيل</span>
        </div>
        <div class='values'>

       ";

           // add content
        foreach($details as $key => $value) {
            $html .=  "<div>$key : $value </div>";
        }

        $html .= "</div>
               </div>";

            return $html;
    }


}
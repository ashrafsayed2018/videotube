<?php 
require_once "includes/classes/VideoInfoControls.php";
// require_once "includes/classes/ButtonProvider.php";
class VideoInfoSection {
    public $con, $video, $userLoggedInObj;

    public function __construct($con,$video,$userLoggedInObj) {
        $this->con = $con;
        $this->video = $video;
        $this->userLoggedInObj = $userLoggedInObj;
    }

    public function create() {


        return $this->createPrimaryInfo() . $this->createSecondryInfo();
    }

    private function createPrimaryInfo() {

        $title = $this->video->getTitle();
        $views = $this->video->getViews();
        $uploadDate = $this->video->getUploadDate();
        $videoControls = new VideoInfoControls($this->video,$this->userLoggedInObj);
        $controls = $videoControls->create();

        return "<div class='videoInfo'>
                   <h1> $title </h1>
                   <div class='bottomSection'>
                      <span class='viewCount'> $views مشاهده   - </span> 
                      <span class='date'>    $uploadDate </span>
                      $controls
                   </div>
                </div>";

    }

    private function createSecondryInfo() {

        $decription = $this->video->getDescription();
        $uploadedBy = $this->video->getUploadedBy();
        $query = $this->con->prepare("SELECT * FROM subscribers WHERE subscribTo = :subscribTo");
    
        $query->bindValue(":subscribTo",$uploadedBy);
    
        $query->execute();

        $subsCount = $query->rowCount();
        $profileButton = ButtonProvider::createUserProfileButton($this->con,$uploadedBy);

        if($uploadedBy == $this->userLoggedInObj->getUserName()) {
            $actionButton = ButtonProvider::createEditVideoButton($this->video->getVideoId());
        } else {
            $subscibeToObj = new User($this->con,$uploadedBy);
            $actionButton = ButtonProvider::createSubcriberButton($this->con,$subscibeToObj,$this->userLoggedInObj);

        }


        return "<div class='secondryInfo'>

                  <div class='topRow'>
                        $profileButton
                        <div class='uploadInfo'>
                           <span class='author'>
                              <a href='profile.php?username=$uploadedBy'>
                                 $uploadedBy
                              </a>
                           </span>
                           <span class='subsCount'>
                      $subsCount مشترك
                           </span>
                         
                        </div>
                        $actionButton
                  </div>

                  <div class='descriptionContainer'>
                     $decription
                  </div>

                </div>";
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
      

    
}

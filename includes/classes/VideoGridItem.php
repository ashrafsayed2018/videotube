<?php 

class VideoGridItem {

    private $video,$largeMode,$con;

    public function __construct($con,$video,$largeMode) {
        $this->con     = $con;
        $this->video     = $video;
        $this->largeMode = $largeMode;

    }


    public function create() {

        $thumbnail = $this->createThumbnail();
        $details   = $this->createDetails();

        $url = "watch.php?id=" .$this->video->getVideoId();
        return "<a href='$url'>

                   <div class='videoGridItem'>
                       $thumbnail
                       $details
                   </div>

                </a>";
    }

    private function createThumbnail() {

        $thumbnail = $this->video->getThumbnail();
       
        $duratiom  = $this->video->getDuration();

         $thumbnailArray = explode(".",$thumbnail);

        if(end($thumbnailArray) == "mp4") {
           
            $theTumbnail = "<video>
                             <source src='$thumbnail'>
                           </video>";
        } else {
            $theTumbnail = "<img src='$thumbnail'>";
        }

        return "<div class='thumbnail'>
                    $theTumbnail
                    <div class='duration'>
                        <span> $duratiom </span>
                    </div>
                </div>";
    }

    private function createDetails() {
        $videoId    = $this->video->getVideoId();
        $title      = $this->video->getTitle();
        $views      = $this->video->getViews();
        $decription = $this->createDescription();
        $timestamp  = $this->time_elapsed_string($this->video->timeStamp());
        $uploadedBy = $this->video->getUploadedBy();

        // get the profile pic of video owner 

        $query = $this->con->prepare("SELECT profilePic FROM users WHERE username = :username");
        $query->bindValue(":username",$uploadedBy);
        $query->execute();

        $profilePic = $query->fetchColumn();

        return "<div class='details'>
                    <h3 class='title'>$title</h3>
                    <a href='profile.php?username=$uploadedBy'>
                        <img class='profilePic' src='$profilePic' style='width:40px;height:40px'>
                        <span class='username'> $uploadedBy </span>
                    </a>
                    
                    <div class='stats'>
                         <span class='viewsCount'>$views مشاهده - </span>
                         <span class='timestamp'>$timestamp </span>
                    </div>
                    $decription
               </div>";
    }

    private function createDescription() {
        
        if(!$this->largeMode) {
            return "";
        } else {
            $description = $this->video->getDescription();
            $description = (mb_strlen($description) > 350) ? substr($description,0,347) ."..." : $description;

            return "<span class='description'>$description</span>";
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
      
}
<?php 

class ProfileData {

    private $con, $profileUserObj;

    public function __construct($con, $profileUsername) {

        $this->con = $con;
        $this->profileUserObj = new User($this->con,$profileUsername);

    }

    public function getProfielUsername() {

        return $this->profileUserObj->getUserName();
    }


    public function userExists() {

        $username = $this->getProfielUsername();

        $query = $this->con->prepare("SELECT * FROM users WHERE username = :username");

        $query->bindValue(":username",$username);

        $query->execute();

        return $query->rowCount() > 0;

    }

    public function getCoverPhoto() {

        return "assets/images/coverPhotos/coverPhoto.jpg";
    }

    public function getProfileUserFullname() {

       return  $this->profileUserObj->getName();
    }


    public function getProfilePic() {

        return  $this->profileUserObj->getUserProfilePic();
     }

     public function getSubscribersCount() {

        return  $this->profileUserObj->getSubscribersCount();
     }


     public function getUserName() {

        return  $this->profileUserObj->getUserName();
     }
 

     public function getUserVideos() {

        $username = $this->getUserName();

        $query = $this->con->prepare("SELECT * FROM videos WHERE uploadedBy = :uploadedBy order by uploadDate desc");

        $query->bindValue(":uploadedBy",$username);
        $query->execute();
    
       $videos =  [] ;

       while($row = $query->fetch(PDO::FETCH_ASSOC)) {
           $videos[] = new Video($this->con,$row,$this->profileUserObj);
       }

       return $videos;



     }


     public function getAllUserDetails() {
         return array(
             "الاسم الكامل" => $this->getProfileUserFullname(),
             "اسم المستخدم"=> $this->getProfielUsername(),
             "عدد المشتركين"=>$this->getSubscribersCount(),
             " اجمالي المشاهدات"  => $this->getTotalViews(),
             "تاريخ الانضمام" => $this->getSignUpDate(),
         );
     }


     private function getTotalViews()  {
           

        $query = $this->con->prepare(" SELECT SUM(views) as 'views' FROM videos WHERE uploadedBy = :username");

        $username = $this->getProfielUsername();

        $query->bindValue(":username",$username);

        $query->execute();

        return $query->fetchColumn();

     }

     private function getSignUpDate() {
          $date = $this->profileUserObj->getUserSignUpDate();

        //  return date("M jS, Y",strtotime($date));

          return $this->time_elapsed_string($date);

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

     private function getTotalLikes() {

     }

     private function getTotalDislikes() {

        // $query = $this->con->prepare(" SELECT SUM(videoId) as 'dislikes' FROM dislikes WHERE username = :username");

        // $username = $this->getProfielUsername();

        // $query->bindValue(":username",$username);

        // $query->execute();

        // $data = $query->fetch(PDO::FETCH_ASSOC);

        // return $data['dislikes'];
     }

    

 

}
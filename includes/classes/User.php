<?php 
class User {
    private $con, $sqlData,$query;

    public function __construct($con,$username) {
        $this->con = $con;

        $this->query = $this->con->prepare("SELECT * FROM users WHERE username = :un");

        $this->query->bindValue(":un",$username);
        $this->query->execute();
    
       $this->sqlData = $this->query->fetch(PDO::FETCH_ASSOC);
     
 

    }

       public static function isLoggedIn() {
        return isset($_SESSION["userLoggedIn"]);
    }
    
    // get the user id 

    public function getUserId() {
        return $this->sqlData['id'];
    }

    // get the username 

    public function getUserName() {

        if($this->query->rowCount() > 0) {
         return $this->sqlData['username'];
      }
    }

    public function getFirstName() {
        return $this->sqlData['firstName'];
    }


    public function getLastName() {
        return $this->sqlData['lastName'];
    }


    // get the user name 

     public function getName() {
        return  $this->getFirstName() . " " . $this->getLastName();
     }

    // get the user email 

    public function getUserEmail() {
        return $this->sqlData['email'];
    }

    // get the profilePic 

    public function getUserProfilePic() {
      
        return $this->sqlData['profilePic'];
        
    }

    // get user singup date

    public function getUserSignUpDate() {
        return $this->sqlData['signupDate'];
    }

    // fuction to check the subscribed users 

    public function isSubscribedTo($subscribTo) {

        $username = $this->getUserName();

        $this->query = $this->con->prepare("SELECT * FROM subscribers WHERE subscribTo = :subscribTo AND subscribFrom = :subscribFrom");

        $this->query->bindValue(":subscribTo",$subscribTo);
        $this->query->bindValue(":subscribFrom",$username);

        $this->query->execute();

        return $this->query->rowCount() > 0;
    }


      // function to get the subscribers count

      public function getSubscribersCount() {

        $username = $this->getUserName();

        $this->query = $this->con->prepare("SELECT * FROM subscribers WHERE subscribTo = :subscribTo");

        $this->query->bindValue(":subscribTo",$username);

        $this->query->execute();

        return $this->query->rowCount();
    }


    // method to get the subscribtions which the user make to the other user 

    public function getSubscriptions() {
        


        $subscribFrom = $this->getUserName();

        $query = $this->con->prepare("SELECT subscribTo FROM subscribers WHERE subscribFrom = :subscribFrom");

        $query->bindValue(":subscribFrom",$subscribFrom);

        $query->execute();

        $subs = array();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {

            $user = $row["subscribTo"];
             array_push($subs,$user);
        }

     return $subs;
    }
}
<?php 

class Account {
   
    private $con;

    private $errorsArray = array();

    public function __construct($con) {
        $this->con = $con;
    }

    public function register($fn,$ln,$un,$em,$em2,$pw,$pw2) {
           $this->ValidateFirstName($fn);
           $this->ValidateLastName($ln);
           $this->ValidateUsername($un);
           $this->ValidateEmails($em,$em2);
           $this->ValidatePasswords($pw,$pw2);

           if(empty($this->errorsArray)) {
               return $this->insertUserDetails($fn,$ln,$un,$em,$pw);
           } else {
               return false;
           }

    }

    public function updateDetails($fn,$ln,$em,$un) {

        $this->ValidateFirstName($fn);
        $this->ValidateLastName($ln);
        $this->ValidateNewEmail($em,$un);

        if(empty($this->errorsArray)) {

            $query = $this->con->prepare("UPDATE users SET firstName = :fn, lastName = :ln, email = :em WHERE username = :un");

            $query->bindValue(":fn",$fn);
            $query->bindValue(":ln",$ln);
            $query->bindValue(":em",$em);
            $query->bindValue(":un",$un);

            return $query->execute();
        } else {
            return false;
        }
    }


    public function updatePassword($OldPass,$pass1,$pass2,$un) {

         $this->validateOldPassword($OldPass,$un);
         $this->ValidatePasswords($pass1,$pass2);

        if(empty($this->errorsArray)) {

             // hash password 
            $pass1 =  hash("sha512", $pass1);
            $query = $this->con->prepare("UPDATE users SET password = :pw WHERE username = :un");

            $query->bindValue(":pw",$pass1);
            $query->bindValue(":un",$un);

            return $query->execute();
        } else {
            return false;
        }
    }

    private function validateOldPassword($OldPass,$un) {
    // hash password 
    $OldPass =  hash("sha512", $OldPass);
   
    $query = $this->con->prepare("SELECT * FROM users WHERE username =:un AND password =
    :pw");
    $query->bindValue(":un",$un); 
    $query->bindValue(":pw",$OldPass);

    $query->execute();

    if($query->rowCount() == 0) {
    
          array_push($this->errorsArray,Constants::$passwordOldPassword);
    }
    }


    private function insertUserDetails($fn,$ln,$un,$em,$pw){
        
        // hash password 
        $pw =  hash("sha512", $pw);
        $profilePic = "assets/images/profilePictures/default.png";
    

        // insert the user details into the database 

        $query = $this->con->prepare("INSERT INTO users (firstName,lastName,username,email,password,profilePic) VALUES (:fn,:ln,:un,:em,:pw,:profilePic)");

        $query->bindValue(":fn",$fn);
        $query->bindValue(":ln",$ln);
        $query->bindValue(":un",$un);
        $query->bindValue(":em",$em);
        $query->bindValue(":pw",$pw);
        $query->bindValue(":profilePic",$profilePic);

        return  $query->execute();

    }

    private function ValidateFirstName($fn) {

        if(mb_strlen($fn) < 2 || mb_strlen($fn) > 25) {
            array_push($this->errorsArray,Constants::$firstNameCharacters);
        }
    }

    private function ValidateLastName($ln) {

        if(mb_strlen($ln) < 2 || mb_strlen($ln) > 25) {
            array_push($this->errorsArray,Constants::$lastNameCharacters);
        }
    }

    private function ValidateUsername($un) {

        if(mb_strlen($un) < 5 || mb_strlen($un) > 25) {
            array_push($this->errorsArray,Constants::$usernameCharacters);

            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindValue(":username",$un);

        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorsArray,Constants::$usernameTaken);
        }
    }

    private function ValidateEmails($em,$em2) {

        if(mb_strlen($em) < 5 ) {
            array_push($this->errorsArray,Constants::$emailCharacters);
            return;
        }

        if($em != $em2) {
            array_push($this->errorsArray,Constants::$emailsNotMatching);

            return;
        }
       
        if(!filter_var($em,FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorsArray,Constants::$emailInvalid);
            return;
        }
        $query = $this->con->prepare("SELECT * FROM users WHERE email=:email");
        $query->bindValue(":email",$em);

        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorsArray,Constants::$emailTaken);
        }
    }

    private function ValidateNewEmail($em,$un) {

        if(mb_strlen($em) < 5 ) {
            array_push($this->errorsArray,Constants::$emailCharacters);
            return;
        }
       
        if(!filter_var($em,FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorsArray,Constants::$emailInvalid);
            return;
        }
        $query = $this->con->prepare("SELECT * FROM users WHERE email =:email and username != :un");
        $query->bindValue(":email",$em);
        $query->bindValue(":un",$un);

        $query->execute();

        if($query->rowCount() != 0) {
            array_push($this->errorsArray,Constants::$emailTaken);
        }
    }

    private function ValidatePasswords($pw,$pw2) {

        if(mb_strlen($pw) < 5  || mb_strlen($pw) > 25) {
            array_push($this->errorsArray,Constants::$passwordCharacters);
            return;
        }

        if($pw != $pw2) {
            array_push($this->errorsArray,Constants::$passwordsNotMatching);

            return;
        }

        if(preg_match("/[^A-Za-z0-9]/",$pw)) {

            array_push($this->errorsArray,Constants::$passwordOnlyLettersAndDigits);

            return;
        }

    
    }


    public function login($un,$pw) {

     // hash password 
      $pw =  hash("sha512", $pw);
   
      $query = $this->con->prepare("SELECT * FROM users WHERE username =:un AND password =
      :pw");
      $query->bindValue(":un",$un); 
      $query->bindValue(":pw",$pw);

      $query->execute();

      if($query->rowCount() == 0) {
         array_push($this->errorsArray,Constants::$loginFailed);
         return false;
      } else {
         return true;
      }


 }
    public function getError($error) {
        if(in_array($error,$this->errorsArray)) {
            return "<span class='errorMessage'>$error </span>";
        }
    }

    // get the first error in errorsArray 

    public function getFirstError() {
        
        if(!empty($this->errorsArray)) {
            return $this->errorsArray[0];
        } else {
            return "";
        }
    }
 }
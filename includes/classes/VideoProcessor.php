<?php 
class VideoProcessor{

    private $con;
    private $size_limit = 500000000;
    private $allowedTypes = array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg","png");
    private $ffmpegPath;
    private $ffprobePath;
    public function __construct ($con) {
       $this->con = $con;

       $this->ffmpegPath = realpath("ffmpeg/bin/ffmpeg.exe");
       $this->ffprobePath = realpath("ffmpeg/bin/ffprobe.exe");


    }


    // method to upload the videouploaddata 

    public function upload($videoUploadData) {

        $targetDir = "uploads/videos/";

        $videoData = $videoUploadData->videoDataArray;

        $tempFilePath = $targetDir . uniqid(). basename($videoData['name']);

        $tempFilePath = str_replace(" ", "_", $tempFilePath);

        $isValidData = $this->processData($videoData,$tempFilePath);

        if(!$isValidData) {
           return false;
        }


        if(move_uploaded_file($videoData['tmp_name'],$tempFilePath)) {
           
            // create the final file path which is mp4 extension 

            $finalFilePath = $targetDir . uniqid() . ".mp4";

            if($this->insertVideoData($videoUploadData,$finalFilePath)) {
                // echo "inserted successfully";
            }

                if(!$this->convertVideoToMp4($tempFilePath,$finalFilePath)) {
                    return false;
                }
    
                if(!$this->deleteFile($tempFilePath)) {
                    return false;
                }
        

            if(!$this->generateThumbnails($finalFilePath)) {
                echo " could not genreate thumbnail\n";

                return false;
            }

            return true;
         
        }

    }

    // method to process the data 
     private function processData($videoData,$filePath) {

        $videoType = pathinfo($filePath, PATHINFO_EXTENSION);

        if(!$this->isValidSize($videoData)) {
            echo "file is too large is more than the " .$this->size_limit . " bytes";
            return false;
        }

        else if(!$this->isValidType($videoType)) {
            echo $videoType . " not valid type";

            return false;
        }

        else if($this->hasError($videoData)) {
            echo "error code : " .$videoData['error'];
            return false;
        }

        return true;

     }

     private function isValidSize($videoData) {
       
        return $videoData['size'] <= $this->size_limit;
     }

     private function isValidType($type) {
         $lowerCased = strtolower($type);

         if(in_array($lowerCased,$this->allowedTypes)) {
             return true;
         }
     }

     private function hasError($videoData) {
          return $videoData['error']  != 0;
     }
     private function insertVideoData($uploadData,$filePath) {

        $sql = "INSERT INTO videos (title,uploadedBy,description,privacy,category,filePath) VALUES (:title,:uploadedBy,:description,:privacy,:category,:filePath)";

       $query =  $this->con->prepare($sql);

       $query->bindValue(':title',$uploadData->title);
       $query->bindValue(':uploadedBy',$uploadData->uploadedBy);
       $query->bindValue(':description',$uploadData->description);
       $query->bindValue(':privacy',$uploadData->privacy);
       $query->bindValue(':category',$uploadData->category);
       $query->bindValue(':filePath',$filePath);

       return $query->execute();

     }


     private function convertVideoToMp4($tempFilePath,$finalFilePath) {

        $cmd = "$this->ffmpegPath -i $tempFilePath $finalFilePath 2>&1";
        
        $outputLog = array();

        exec($cmd,$outputLog,$returnCode);
        if($returnCode != 0) {
            // command failed

            foreach($outputLog as $line) {

                echo $line . "<br/>";
            }
            return false;
        }
        return true;
     }

     private function deleteFile($filePath) {

        if(!unlink($filePath)) {
            echo "could not delete the file \n";
            return false;
        }

        return true;
     }

     public function generateThumbnails($filePath) {
         $thumbnailSize = "210x118";
         $numThumbnails = 3;
         $pathToThumbnail = "uploads/videos/thumbnails/";

         // video duration 

         $duration = $this->getVideoDuration($filePath);


         $videoId = $this->con->lastInsertId();

         $this->updateDuration($duration,$videoId);

         for($num = 1 ; $num <= $numThumbnails; $num++) {
             $imageName = uniqid() . ".jpg";
             $interval = ($duration * .8) / $numThumbnails * $num;
             $fullThumbnailPath = $pathToThumbnail . $videoId. "-". $imageName;

             $cmd = "$this->ffmpegPath -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";
        
             $outputLog = array();
     
             exec($cmd,$outputLog,$returnCode);
             if($returnCode != 0) {
                 // command failed
     
                 foreach($outputLog as $line) {
     
                     echo $line . "<br/>";
                 }
             }

             $selected = ($num == 1) ? 1: 0;
             // query to insert the thumbnail image 

             $query = $this->con->prepare("INSERT INTO thumbnails (videoId,filePath,selected) VALUES (:videoId,:filePath,:selected) ");
             $query->bindValue(":videoId",$videoId);
             $query->bindValue(":filePath",$fullThumbnailPath);
             $query->bindValue(":selected",$selected);

             $success = $query->execute();
             if(!$success) {
                 echo "failed to insert thumbnail";
                 return false;
             }
           
         }
         return true;

    }

     private function getVideoDuration($filePath) {
        return (int) shell_exec("$this->ffprobePath -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
       
    }

    private function updateDuration($duration,$videoId) {

       $hours = floor($duration / 3600);
       $mins  = floor(($duration - ($hours * 3600)) / 60);
       $seconds = floor($duration % 60);

       $hours = ($hours < 1) ? "" : $hours . ":";

       $mins = ($mins < 10) ? "0" . $mins . ":": $mins . ":";

       $seconds = ($seconds < 10) ? "0" . $seconds . ":": $seconds . ":";

       $duration = $hours.$mins.$seconds;

       // update the duration 

       $query = $this->con->prepare("UPDATE  videos SET duration = :duration WHERE id=:videoId");

       $query->bindValue(":duration",$duration);
       $query->bindValue(":videoId",$videoId);
       $query->execute();

       
    }
}

<?php 


class VideoPlayer{

    private $video;

    public function __construct($video) {
        $this->video = $video;
    }

    //  method to create the video player

    public function create($autoPlay) {

        if($autoPlay) {
            $autoPlay = "autoplay";
        }
        else {
            $autoPlay = "";
        }

        // get the file path
        $videoId = $this->video->getVideoId();
        $filePath = $this->video->getFilePath();

        return "<video class='videoPlayer' id='$videoId' controls $autoPlay>
                    <source src='$filePath' type='video/mp4' />
                    Your Browser Does not support the video tag
                </video>";
    }
}
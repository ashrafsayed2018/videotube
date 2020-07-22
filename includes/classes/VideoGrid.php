<?php 

class VideoGrid {

    private $con,$userLoggedInObj;
    private $largeMode = false;
    private $gridClass = "videoGrid";

    public function __construct($con,$userLoggedInObj) {

        $this->con = $con;
        $this->userLoggedInObj = $userLoggedInObj;

    }

    public function create($videos, $title, $showFilter) {

  
        if($videos == null) {

            $gridItems = $this->generateItems();

        } else {

            $gridItems = $this->generateItemsFromVideos($videos);
        }

        $header = "";

        if($title != null) {

            $header = $this->creareGridHeader($title,$showFilter);
        }

        return "$header
                    <div class='$this->gridClass'>

                        $gridItems
                    
                    </div>";

    }

    public function generateItems() {

        // generates random videos 

        $query = $this->con->prepare("SELECT * FROM videos ORDER BY RAND () LIMIT 15");

        $query->execute();
        
        $elementsHtml = "";

   
        while($row = $query->fetch(PDO::FETCH_ASSOC)) {

        $video = new Video($this->con,$row,$this->userLoggedInObj);

        $item = new VideoGridItem($this->con,$video,$this->largeMode);

        $elementsHtml .= $item->create();
        }

        return $elementsHtml;

    }

    public function generateItemsFromVideos($videos) {


        $elementsHtml = "";

        foreach($videos as $video) {

            $item = new VideoGridItem($this->con,$video,$this->largeMode);

            $elementsHtml .= $item->create();

        }

        return $elementsHtml;

    }

    public function creareGridHeader($title,$showFilter) {
        
        $filter = "";

        // create filter 

        if($showFilter) {
          

           $link = "http://" .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

           $urlArray = parse_url($link);

           $query = $urlArray['query'];

           $queryString = parse_str($query,$parameters);
          
           unset($parameters['orderBy']);

           $newQuery = http_build_query($parameters);

           $newUrl = basename($_SERVER['PHP_SELF']) . "?" . $newQuery;

          $filter = "<div class='right'>
                        <span> ترتيب حسب  </span>
                        <a href='$newUrl&orderBy=uploadDate'>تاريخ التحميل  |</a>
                        <a href='$newUrl&orderBy=views'>الاكثر مشاهده</a>
                      </div>";

            

        }

        return "<div class='videoGridHeader'>
                    <div class='left'>
                         $title
                    </div>
                    $filter
               </div>";
    }


    // create large grid for the search page 

    public function createLarge($videos,$title,$showFilter) {

        $this->gridClass .= " large";
        $this->largeMode   = true;

        return $this->create($videos,$title,$showFilter);
    }
}
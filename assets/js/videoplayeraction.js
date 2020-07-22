function likeVideo(button,videoId) {
 
    // send ajax to like video page 

    $.ajax({
        method: "post",
        url   : "ajax/likeVideo.php",
        data   : {videoId:videoId},
        success : function (data) {

            var likeButton = $(button);
            var disLikeButton = $(button).siblings(".disLikeButton");

            likeButton.addClass('active');
            disLikeButton.removeClass('active');

            result = JSON.parse(data);
           
            updateLikesValue(likeButton.find('.text'),result.likes);
            updateLikesValue(disLikeButton.find('.text'),result.dislikes);

            if(result.likes < 0) {

                likeButton.removeClass('active');

                likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png");

            }
            else {
               
                likeButton.find("img:first").attr("src","assets/images/icons/thumb-up-active.png");
            }



            disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png");


        }
    });


}






// dislike function 


function dislikeVideo(button,videoId) {
 
    // send ajax to like video page 

    $.ajax({
        method: "post",
        url   : "ajax/dislikeVideo.php",
        data   : {videoId:videoId},
        success : function (data) {

            var disLikeButton = $(button);
            var likeButton = $(button).siblings(".likeButton");

            disLikeButton.addClass('active');
            likeButton.removeClass('active');
            result = JSON.parse(data);
            updateLikesValue(likeButton.find('.text'),result.likes);
            updateLikesValue(disLikeButton.find('.text'),result.dislikes);

            if(result.dislikes < 0) {


                disLikeButton.removeClass('active');
                disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down.png");
           
            }
            else {
             
                disLikeButton.find("img:first").attr("src","assets/images/icons/thumb-down-active.png");
             
            }

            likeButton.find("img:first").attr("src","assets/images/icons/thumb-up.png");


        }
    });
}


function updateLikesValue(element,num) {

    var likesCountVal = element.text() || 0;

    element.text(parseInt(likesCountVal) + parseInt(num));

}






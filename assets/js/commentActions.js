function postComment(button,postedBy,videoId,replyTo,containerClass) {

     var textarea = $(button).siblings('textarea');

     var commentText = $(textarea).val();
   
     // check if comment is empty or not

     if(commentText) {

        $.ajax({
            url : "ajax/postComment.php",
            method : "post",
            data   : {commentText : commentText,postedBy: postedBy,videoId : videoId,responseTo: replyTo},
            success : function(comment) {

                if(!replyTo) {
                    $('.' + containerClass).prepend(comment);
                } else {
                    $(button).parent().siblings("." +containerClass).append(comment)
                }
          
                $(textarea).val("");
            }
        });

     } else {
         alert(" You Can not Post an Empty Comment");
     }

}

function toggleReply(button) {

    var parent = $(button).closest(".itemContainer");
    var commentForm = parent.find(".commentForm").first();
    commentForm.toggleClass("hidden");
  
}

function likeComment(button,videoId,commentId) {

        // send ajax to like comment page 

        $.ajax({
            method: "post",
            url   : "ajax/likeComment.php",
            data   : {videoId:videoId,commentId:commentId},
            success : function (numtoCange) {
    
                var likeButton = $(button);
                var disLikeButton = $(button).siblings(".disLikeButton");
    
                likeButton.addClass('active');
                disLikeButton.removeClass('active');

                var LikeCount = $(button).siblings(".likesCount");

                updateLikesValue(LikeCount,numtoCange);
    
                if(numtoCange < 0) {
    
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

function dislikeComment(button,videoId,commentId) {
    
    $.ajax({
        method: "post",
        url   : "ajax/dislikeComment.php",
        data   : {videoId:videoId,commentId:commentId},
        success : function (numtoCange) {

            var  disLikeButton  = $(button);
            var  likeButton = $(button).siblings(".likeButton");

            disLikeButton.addClass('active');
            likeButton.removeClass('active');
            var LikeCount = $(button).siblings(".likesCount");

            updateLikesValue(LikeCount,numtoCange);

            if(numtoCange > 0) {

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

function getReplies(button,commentId,videoId) {
     $.ajax({
         url: "ajax/getCommentReplies.php",
         type : "POST",
         data : {commentId : commentId,videoId : videoId},
         success :function (replies) {
             
            var Replies = $('<div>').addClass('repliesSection');
            
            Replies.append(replies);
            $(button).replaceWith(Replies);
         }
     })
}

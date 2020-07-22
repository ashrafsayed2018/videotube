
$(function () {
  

    $(".thumbnailItem").on("click",function () {

        var thumbnailId = $(this).attr('id');
        var videoId     = $(this).attr('data-videoId');
        
        $(this).siblings(".thumbnailItem").removeClass("selected");
        
        $(this).addClass("selected");
    
         $.ajax({
            url : "ajax/UpdateThumbnail.php",
            method : "post",
            data : {thumbnailId: thumbnailId,videoId: videoId},
            success : function () {
         
                alert("تم تغيير الصوره المصغره");
               }
            })
    });

});


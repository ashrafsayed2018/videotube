function subscribe(button,subscribeTo,loggedInUser) {
  
    if(subscribeTo == loggedInUser) {
        alert ("نأسف لا بمكنك الاشتراك لقناتك");

        return;
    }

    $.ajax({
        url : "ajax/subscribe.php",
        method : "post",
        data : {subscribeTo:subscribeTo,loggedInUser:loggedInUser},
        success : function (count) {
           if(count != null) {

            $(button).toggleClass('subscribe unsubscibe');

            var buttonText = $(button).hasClass("subscribe") ? "اشتراك": "مشترك";
            $(button).find(".text").text(buttonText);

           } else {
               alert("something went worng ");
           }
        }
    });
}

$(function() {

    var getAllSubscribers = true;
    var subscriberTo = $(".author a").text();

    $.ajax({
    
        url : "ajax/subscribe.php",
        method : "post",
        data : {getAllSubscribers:getAllSubscribers,subscriberTo:subscriberTo},
        success : function (count) {
           if(count != null) {
            $(".edit .text").text($('.edit .text').text());

           } else {
               alert("something went worng ");
           }
        }
    });


    // check the subscriber button if subscribed or subscribe


    var issubscriberTo = $(".author a").text();


    var subscribeFrom = $(".rightIcons .username").text();
    var check = true;

    $.ajax({
        url : "ajax/subscribe.php",
        method : "post",
       
        data : {issubscriberTo:issubscriberTo,subscribeFrom:subscribeFrom,check:check},

        success : function(data) {

            var result = JSON.parse(data);
           var subsCount = result.subCount;

         
           var count = result.isSubscribed;

           if(count > 0) {
         
               $('.subscribeButtonContainer .button').attr('class',"unsubscibe button");

               $('.subscribeButtonContainer .button .text').text('مشترك')
          
           } else {
            $('.subscribeButtonContainer .button').attr('class',"subscribe button");

            $('.subscribeButtonContainer .button .text').text('اشتراك');
           }

        

        }
    })



});



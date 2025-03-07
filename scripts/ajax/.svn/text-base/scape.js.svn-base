// Scape functions --------------------

function post(){
    
    scape = $('#editor').html();

    if (scape != "<span style=\"color: #777\">Express yourself...</span>"
        && scape != "<br>"
        && scape != "<span style=\"color: rgb(119, 119, 119);\">Express yourself...</span>"
        && scape != "<SPAN style=\"COLOR: #777\">Express yourself...</SPAN>"
        && scape != "<SPAN style=\"color: #777\">Express yourself...</SPAN>"){

        hide = new Array();
        show = new Array();

        if($("#radio_hideFrom").is(":checked")){
            $("#privacyList div").each(function(){
                hide.push($(this).attr("id"));
            });
        }else if($("#radio_showTo").is(":checked")){
            $("#privacyList div").each(function(){
                show.push($(this).attr("id"));
            });
        }

        ajaxCreateScape(scape,show,hide);

    }

}
function ajaxCreateScape(scape,show,hide){

    // Title had a bug, returns null for some reason =S, using $("#headingBox").val() to get title directly.   
    if (scape.replace(/(<[^img].*?>)|(&nbsp;)/g, '').replace(/\s/g,"") != "" || $("#headingBox").val() != "") {
        
        show = show.join("|");
        hide = hide.join("|");
        // Sends a request to the scape controller to create a scape post.
        aniLockPub();
 
        $.ajax({
            type: "POST",
            url: "/logged/ajax/createscape",
            data: "scape=" + encodeURIComponent(scape) + "&title=" + encodeURIComponent($("#headingBox").val()) + "&show=" + encodeURIComponent(show)+ "&hide=" + encodeURIComponent(hide),
            error: function(){               
                aniDisplayScapeError();
            },
            success : function(scapeid){

                if(scapeid.match(/^\d*$/) && scapeid != ""){

                    if (scape != "" || $("#headingBox").val() != ""){
                        getScape(scapeid);
                        aniUnlockPub();
                    }
                }else{
                    aniDisplayScapeError();
                }
            }
        });
    }
}

function getScape(scapeid){

    // Sends a request to the scape controller to update the timeline with the latest scape.

    $.ajax({
        type:"POST",
        url:"/logged/ajax/updatetimeline",
        data:"scapeid="+scapeid,
        success: function(scapeData) {

            aniDisplayPostedScape(scapeData);

        }
    });
}

function ajaxDisplayTimeline(location,userid,loadMore){

    // Sends a request to the scape controller to display the timeline with the latest scapes.
    if(!window.noMoreScapes){
        $("#loadMoreBtn").hide();
        $("#timelineFeed").append('<img src="/graphics/en/UI/progress_bar_large_green.gif" style="margin-bottom: 15px;" class="progressBar" />');

        $.ajax({
            type:"POST",
            url:"/logged/ajax/displaytimeline",
            data:"location=" + location + "&userid=" + userid+ "&loadMore=" + loadMore,
            success: function(data) {

                if(data == ""){
                    window.noMoreScapes = true;
                }
                $(".progressBar").remove();
                $("#timelineFeed").append(data);
                $("#loadMoreBtn").show();
                $(data).hide();
                $(data).fadeIn("slow");


            }
        });
    }
}

function ajaxLoadMoreTimeline(location,userid){

    loadMore = 1;
    $.ajax({
        type:"POST",
        url:"/logged/ajax/displaytimeline",
        data:"location=" + location + "&userid=" + userid + "&loadMore=" + loadMore,
        success: function(data) {

            $("#timelineFeed").append(data);
        //            $("#timelineFeed div.scape").slideDown("slow");
        //            $("#noPosts").remove();

        }
    });
}

function ajaxEditScape(scape,title,scapeid){


    $.ajax({
        type: "POST",
        url: "/logged/ajax/editscape/format/xml",
        data: 	"scape=" + encodeURIComponent(scape) +"&title=" + encodeURIComponent(title) +"&scapeid=" + scapeid,
        success : function(data){

            $(data).find('scapeContent').each(function(){
                content = decodeURIComponent($(this).find("content").text());
                
                title = decodeURIComponent($(this).find("title").text());
            });
            
            $("#scapeContent").show();
            $("#scapeContent").html(content);
            $("#editEditor").html("");
            $("#editUpperPanel").hide();
            $("#editPublisher").hide();
            $(".replyArea").show();
            $("#noReply").html("");
            $("#headingEditor").hide();
            $("#scapeDeleteBtn").show();
            $("#scapePrivacyBtn").show();
            $("#postTitle").html(title);
            $("#postTitle").show();
            $(".replyActions").show();
            $(".showAllComments").show();

            revertToolTipMarkup = function(){
                $("#editTooltipContent").html("<strong>Go ahead and start editing!</strong><br />Don't forget to click here to save your changes.");
            };

            $("#editTooltipContent").html("<strong> Changes successfully saved ! <strong>");
            $('.editInfo').fadeIn(400,function(){
                window.setTimeout('$(\'.editInfo\').fadeOut(400,revertToolTipMarkup)', 3000);
            });
            

        }
    });

}

function loadMoreTimeline(){

    $.ajax({
        type: "POST",
        url: "/logged/ajax/editscape/format/xml",
        data: 	"scape=" + encodeURIComponent(scape) +"&title=" + title +"&scapeid=" + scapeid,
        success : function(data){

            $(data).find('scapeContent').each(function(){
                content = decodeURIComponent($(this).find("content").text());
                title = decodeURIComponent($(this).find("title").text());
            });

            $("#scapeContent").attr("style","");
            $("#scapeContent").html(content);
            $("#postTitle").html(title).show();
            $("#postTitleEditor").val(title);
            $("#headingEditor").hide();
            $("#editEditor").html("");
            $("#editUpperPanel").attr("style","display:none;");
            $("#editPublisher").attr("style","display:none;");
            $(".replyArea").attr("style","");
            $("#noReply").html("");

            revertToolTipMarkup = function(){
                $("#editTooltipContent").html("<strong>Go ahead and start editing!</strong><br />Don't forget to click here to save your changes.");
            };

            $("#editTooltipContent").html("<strong> Changes successfully saved ! <strong>");
            $('.editInfo').fadeIn(400,function(){
                window.setTimeout('$(\'.editInfo\').fadeOut(400,revertToolTipMarkup)', 3000);
            });


        }
    });

}

function ajaxMakeThumb(text){

    images = text.match(/(http\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(?:\/\S*)?(?:[a-zA-Z0-9_])+\.(?:jpg|jpeg|gif|png))/gi);

    $.ajax({
        type: "GET",
        url: "/logged/ajax/imagetest",
        data: 	"image1=" + images[0] + "&image2=" + images[1] + "&image3=" + images[2] + "&image4=" + images[3]

    });
    
}


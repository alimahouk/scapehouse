// General functions -------------------------------

function ajaxSendTz(gmtHours){

    // Sends the data in the timezone cookie to the application server.

    $.ajax({
        type:"POST",
        url: "/index/tzcookie",
        data: "tz=" + gmtHours

    });
}

function prefixConstruct(idType){

    switch(true){
        case(idType == "scape"):
            return idPrefix = "S";
            break;
        case(idType == "reply"):
            return idPrefix = "R";
            break;
        case(idType == "photo"):
            return idPrefix = "P";
            break;
        case(idType == "comment"):
            return idPrefix = "C";
            break;
    }

}

function ajaxDelete(elmid,idType,page,username) {

    // Sends a request to the ajax controller to delete an element.

    $.ajax({
        type:"POST",
        url: "/logged/ajax/delete",
        data: "elmid=" + elmid + "&idType=" + idType,
        success: function(){

            idPrefix = prefixConstruct(idType);

            if(page == "scapePage"){
                var url = "/index";
                $(location).attr("href",url);
            } else {
                if(idType != "photo"){
                    $("#"+ idPrefix + elmid).slideUp("slow", function(){
                        $("#"+ idPrefix + elmid).remove();
                    });
                }else {
                    window.location = "/"+username+"/gallery";
                }
            }
        }
    });
}

function ajaxUIWListUsers(jsonids,type){

    $.ajax({
        type:"POST",
        url:"/logged/ajax/uiwlistusers",
        data: "jsonids=" + jsonids + "&type=" + type,
        error: function(){

        },
        success: function(html) {
            $(".UIWindow").hide();
            $("#userList").html(html).show();
            $("#pageOverlay").show();
        }
    });
}

function ajaxUIWReply(replyid){

    $.ajax({
        type:"POST",
        url:"/logged/ajax/uiwreply",
        data: "replyid=" + replyid,
        error: function(){

        },
        success: function(html) {
            $(".UIWindow").hide();
            $("#replyWindowContainer").html(html).show();
            $("#pageOverlay").show();
        }
    });
}

function ajaxUIWPhoto(photoid){

    $.ajax({
        type:"POST",
        url:"/logged/ajax/uiwphoto",
        data: "photoid=" + photoid,
        error: function(){

        },
        success: function(html) {
            $(".UIWindow").hide();
            $("#photoWindowContainer").html(html).show();
            $("#pageOverlay").show();
        }
    });
}

function ajaxUIWFeedback(){

    $.ajax({
        url:"/logged/ajax/uiwfeedback",
        error: function(){

        },
        success: function(html) {
            $(".UIWindow").hide();
            $("#feedbackWindowContainer").html(html).show();
            $("#pageOverlay").show();
        }
    });
}

function ajaxSubmitFeedback(type,content,thumbsup,thumbsdown){

        $.ajax({
        type:"POST",
        url:"/logged/ajax/submitfeedback",
        data: "type=" + type + "&content=" + content + "&thumbsup=" + thumbsup + "&thumbsdown=" + thumbsdown,
        error: function(){

        },
        success: function() {
            
        }
    });
}
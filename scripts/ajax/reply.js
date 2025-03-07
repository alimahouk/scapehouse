// Reply functions --------------------

function ajaxCreateReply(reply,scapeid){

    // Sends a request to the scape controller to create reply on a scape
    if(reply.replace(/(<.*?>)|(&nbsp;)/g, '').replace(/\s/g,"") != ""){
        $("#editor").attr("contenteditable","false");
        $("#editor").css("background-color","#DDD");

        $.ajax({
            type: "POST",
            url: "/logged/ajax/createreply",
            data: "reply=" + encodeURIComponent(reply) + "&scapeid=" + scapeid,
            error: function(){
                $("#replyError").fadeIn(2000,function(){
                    $("#replyError").fadeOut(2000);
                });
                $("#editor").attr("contenteditable","true");
                $("#editor").css("background-color","white");
            },
            success : function(replyid){
                if(is_numeric(replyid) && replyid != ""){

                    $("#editor").html("");

                    ajaxUpdateReplies(replyid);

                    $("#replyConfirmation").fadeIn(2000,function(){
                        $("#replyConfirmation").fadeOut(2000);
                    });
           
                    $("#editor").attr("contenteditable","true");
                    $("#editor").css("background-color","white");
                    
                }else{
                    $("#replyError").fadeIn(2000,function(){
                        $("#replyError").fadeOut(2000);
                    });
                    $("#editor").attr("contenteditable","true");
                    $("#editor").css("background-color","white");
                }
            }
        });
    }
}


function ajaxUpdateReplies(replyid){

    // Sends a request to the scape controller to update the replies under the scape post.
    $.ajax({
        type:"POST",
        url:"/logged/ajax/postedreply",
        data: "replyid=" + replyid,
        error: function(){
          
        },
        success: function(data) {

            $("#batch1").append(data);
            if($.cookie("collapse") == 1) {
                $('div.discussionWall div.replies div.replyBubble, div.discussionWall div.replies a.showAllCommentsBtnLink').css("width", "430px");
                $('html.discussion.loggedIn div.CGReply .editReplyPublisher .editReplyEditor').css("width","417px");
                $('html.discussion.loggedIn div.CGReply .editReplyUpperPanel').css("width","426px");
            }
            $("#replies div.CGReply").slideDown("slow");         

        }
    });
}

function ajaxDisplayReplies(scapeid,limit,offset,batch) {
    // Sends a request to the scape controller to display any replies that exist on a scape post.
    
    
    if ($("#batch"+batch).html() == "" || $("#batch"+batch).html() == null){

        $(".replyBatch").hide();
        $("#batch"+batch).html('<img style="margin-top:30px;" src="/graphics/en/UI/progress_bar_large_green.gif" style="margin-bottom: 15px;" class="progressBar" />').show();
    
        $.ajax({
            type:"POST",
            url:"/logged/ajax/displayreplies",
            data: "scapeid=" + scapeid + "&offset=" + offset + "&limit=" + limit,
            success: function(data) {

                //                    $(".replyBatch").hide();
                $("#batch"+batch).html(data);

                $("#replies div.CGReply").css({
                    display: 'block'
                });
                if($.cookie("collapse") == 1) {
                    $('div.discussionWall div.replies div.replyBubble, div.discussionWall div.replies div.showAllCommentsBtn').css("width", "430px");
                    $('html.discussion.loggedIn div.CGReply .editReplyPublisher .editReplyEditor').attr("style","width:417px;");
                    $('html.discussion.loggedIn div.CGReply .editReplyUpperPanel').attr("style","width:426px;");
                }
                
                if($.cookie("collapse") == 1) {
                    sidebarCollapse();
                } else {
                    sidebarShow();
                }

            }
        });

    }else{
        $(".replyBatch").hide();
        $("#batch"+batch).show();

    }

    

}

function ajaxEditReply(reply,replyid){

    $("#R"+replyid+" .editReplyEditor").attr("contenteditable","false");
    $("#R"+replyid+" .editReplyEditor").css("background-color","#DDD");

    $.ajax({
        type:"POST",
        url:"/logged/ajax/editreply",
        data: "replyid=" + replyid + "&reply=" + encodeURIComponent(reply),
        success: function(data) {
            
            $("#R"+replyid+" .replyBodyTxt").html($("#R"+replyid+" .editReplyEditor").html());

            $(".replyActions").show();
            $(".showAllCommentsBtnHolder").show();
            $(".postActions").show();
            $(".replyArea").show();
            $("#noReply").html("");

            $("#R"+replyid+" .editReplyPublisher").hide();
            $("#R"+replyid+" .editReplyEditor").attr("contenteditable","true");
            $("#R"+replyid+" .editReplyEditor").css("background-color","white");

            $("#R"+replyid+" .replyBodyTxt").show();
            $("#R"+replyid+" .replyBodyTxt").html(data);

            $(".sliderComponent").show();
            $(".likeBtn a").removeClass("unavailable");
            $(".commentBtn a").removeClass("unavailable");
            $(".dislikeBtn a").removeClass("unavailable");
            $(".deleteBtn a").removeClass("unavailable");

            $("#scapeEditBtn").show();
            $("#scapeDeleteBtn").show();
            $("#scapePrivacyBtn").show();

            $(".commentBtn").removeClass("deactivacted").live("click",function(){
                replyid = $(this).parents(".CGReply").attr("id").replace(/R/,"");
                comment(replyid,"reply");
            });
            $(".likeBtn").removeClass("deactivacted").live("click",function(){
                replyid = $(this).parents(".CGReply").attr("id").replace(/R/,"");
                ajaxLike(replyid,'reply');
            });
            $(".dislikeBtn").removeClass("deactivacted").live("click",function(){
                replyid = $(this).parents(".CGReply").attr("id").replace(/R/,"");
                ajaxDislike(replyid,'reply');
            });
            $(".deleteBtn").removeClass("deactivacted").live("click",function(){
                if($(this).hasClass("reply")){
                    replyid = $(this).parents(".CGReply").attr("id");
                    $('#'+replyid+' .deleteConfirmation').slideDown();
                }
            });
            window.replyContentOri = null;
        }
        
    });
    


}
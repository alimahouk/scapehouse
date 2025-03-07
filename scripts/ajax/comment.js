// Comments functions --------------------------

/* Creates a comment on the specified element, the region where the
 * comment takes place is determined by the location variable.
 * arguments ----------
 * comment : the comment string
 * elmid : the id of the element the comment is going to be created on
 * elmname : the name of the element the comment is going to be created on, for eg. reply, photo etc.
 * flag: used to specify a region where the comment is inputed, for eg, the comment box of the comment display window.
 */
function ajaxCreateComment(comment,elmid,idType,location){

    if(comment != "<br>" && comment.replace(/(<.*?>)|(&nbsp;)/g, '').replace(/\s/g,"") != "" && elmid != null && idType != null){

        $(".minieditor").attr("contenteditable","false");
        $(".minieditor").css("background-color","#DDD");
        
        idPrefix = prefixConstruct(idType);

        $.ajax({
            type:"POST",
            url:"/logged/ajax/createcomment",
            data: "comment=" + encodeURIComponent(comment) + "&elmid=" + elmid + "&idType=" + idType,
            success: function(commentid) {

                if (location == "commentWindow"){
                    
                    $('.minieditor').html("");
                    $(".minieditor").attr("contenteditable","true");
                    $(".minieditor").css("background-color","white");

                    ajaxLatestComment(commentid);
                    
                } else {
                    // Animation ----

                    $("#minieditor"+elmid).html("<br>");
                    showAllCommentsBtnMarkup = $("#"+idPrefix+elmid+" .commentCount").html();
                    $("#"+idPrefix+elmid+" .showAllCommentsBtnLink").hide();
                    $("#"+idPrefix+elmid+" .showAllCommentsBtnLink").fadeIn();
                    $("#"+idPrefix+elmid+" .showAllCommentsBtnLink").css("display","block");
                    $("#"+idPrefix+elmid+" .showAllCommentsBtnLink").html("<span class=\"UIIcon px16 comment\"></span>Comment posted!");

                    switch(true){
                        case(showAllCommentsBtnMarkup == null):
                            showAllCommentsBtnMarkup = "<span class=\"UIIcon px16 comment\"></span>Show <span class=\"commentCount\">1</span> comment.";
                            noOfComments = null;
                            break;
                        case(showAllCommentsBtnMarkup.match(/\d/) >= 1):
                            noOfComments = showAllCommentsBtnMarkup.match(/\d/);
                            noOfComments ++;
                            showAllCommentsBtnMarkup = "<span class=\"UIIcon px16 comment\"></span>Show all <span class=\"commentCount\">"+noOfComments+"</span> comments.";
                            noOfComments = null;
                            break;
                    }


                    function replaceMarkup() {
                        $("#"+idPrefix+elmid+" .showAllCommentsBtnLink").hide();
                        
                        $("#"+idPrefix+elmid+" .showAllCommentsBtnLink").fadeIn(400,function () {
                            $(".minieditor").attr("contenteditable","true");
                            $(".minieditor").css("background-color","white");
                            // This ones for the one on the photo page ;)
                            $("#minieditor").html("<br>");
                        });

                        $("#"+idPrefix+elmid+" .showAllCommentsBtnLink").html(showAllCommentsBtnMarkup);
                        $("#"+idPrefix+elmid+" .showAllCommentsBtnLink").css("display","block");
                    }

                    setTimeout(replaceMarkup, 3000);

                }

                
            }
        });
    }
}

function ajaxLatestComment(commentid){

    $.ajax({
        type:"POST",
        url:"/logged/ajax/postedcomment",
        data: "commentid=" + commentid,
        success: function(data) {

            $("#comments").append(data);

        }
    });

}

function ajaxDisplayComments(elmid,idType){

    idPrefix = prefixConstruct(idType);

    if(elmid != null && idType != null){


        $("#replyContent").html("");
        if(idType == "photo"){
            $("#comments").html('<img class="spinningIndicator" src="/graphics/en/UI/spinning_progress_indicator_blue.gif" style="margin-left:200px;margin-top:25px;"/>');
        }else{
            $("#comments").html('<img class="spinningIndicator" src="/graphics/en/UI/spinning_progress_indicator_blue.gif" style="margin-left:270px;margin-top:25px;"/>');
        }
    
        $.ajax({
            type:"POST",
            url:"/logged/ajax/displaycomments",
            data: "elmid=" + elmid + "&idType=" + idType,
            success: function(data) {
                // Delete confirm markup work around
                $(".deleteConfirmation").slideUp();
                $(".deleteConfirmation").css({
                    display:"none"
                });


                var replyMarkup = $("#"+idPrefix+elmid+" div.replyContent").html();

                var style = $("#"+idPrefix+elmid).attr("class");
                var replyid = $("#"+idPrefix+elmid).attr("id");

                $("#commentWindowReply").removeAttr("class");

                $("#commentWindowReply").addClass(style);
                $("#commentWindowReply").attr("replyid",replyid);
                $("#replyContent").css({
                    display: 'none'
                });
                $("#replyContent").fadeIn();
                $("#replyContent").html(replyMarkup);
                $("#comments").css({
                    display: 'none'
                });
                $("#comments").fadeIn();
                $("#comments").html(data);

            }
        });
    }
}

function ajaxEditComment(comment,id){

    $.ajax({
        type:"POST",
        url:"/logged/ajax/editcomment",
        data: "comment=" + encodeURIComponent(comment) + "&commentid=" + id,
        success: function(data) {
            
            $("#C"+id+" .commentTxt").removeClass("editMinieditor");
            $("#C"+id+" .commentTxt").removeAttr("contenteditable");
            $("#C"+id+" .commentTxt").removeAttr("id");

            $("#C"+id+" .commentTxt").html(data);
            $("#C"+id+" .editBtn").html("Edit");
            $(".commentActions").show();
            $("#commentWindowCommentBox").show();
            $("#commentBoxHeading").html("Leave a comment...");
            window.commentContentOri = null;
        }
    });
}
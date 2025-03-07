function ajaxMakeCurrent(photoid){

    $.ajax({
        type:"POST",
        url: "/logged/ajax/makecurrent",
        data: "photoid=" + photoid
    });
        
}

function ajaxDisplayPhoto(photoid){


    $.ajax({
        type:"POST",
        url: "/logged/ajax/displayphoto/format/xml",
        data: "photoid=" + photoid,
        beforeSend:function(){
                
        },
        success : function(data){

            $(data).find('photoDetails').each(function(){

                $("#photo").attr("src",decodeURIComponent($(this).find("photoLink").text()));
                $(".caption").html(decodeURIComponent($(this).find("caption").text()));
                $("#locationHolder").html(decodeURIComponent($(this).find("location").text()));
                $(".uploadTimestamp").html(decodeURIComponent($(this).find("time").text()));
                $(".photoActions").html(decodeURIComponent($(this).find("photoActions").text()));
                $("#confirmDelHolder").html(decodeURIComponent($(this).find("confirmDel").text()));
                $(".showAllComments").html(decodeURIComponent($(this).find("showComments").text()));
                $(".likesAndDislikes").html(decodeURIComponent($(this).find("likesDislikes").text()));
                $(".userActions").html(decodeURIComponent($(this).find("userActions").text()));
                $("#saveChanges").html(decodeURIComponent($(this).find("saveChangesBtn").text()));
                $("#postCommentBtn").html(decodeURIComponent($(this).find("commentBtn").text()));
                $("#postCommentBtnWindow").html(decodeURIComponent($(this).find("commentBtnWindow").text()));


                $(".mainContainer").attr("id","P"+decodeURIComponent($(this).find("photoid").text()));

                $("#currentPic").html(window.cur);
                
                ajaxDisplayComments(photoid,"photo");
                
            });                            

        }
    });
}

function ajaxEditPhotoDetails(photoid,caption,location){


if (photoid != null){
    $("#editCaption").attr("disabled","true");
    $("#editCaption").css("background-color","#DDD");

    $("#editLocation").attr("disabled","true");
    $("#editLocation").css("background-color","#DDD");

    $.ajax({
        type:"POST",
        url: "/logged/ajax/editphotodetails",
        data: "photoid=" + photoid + "&caption=" + encodeURIComponent(caption) + "&location=" + encodeURIComponent(location),
        success : function(){

            $("#editCaption").removeAttr("disabled");
            $("#editCaption").css("background-color","white");
            $("#caption").html($("#editCaption").val());

            $("#editLocation").removeAttr("disabled");
            $("#editLocation").css("background-color","white");
            $("#location").html($("#editLocation").val());

            if(location != ""){
                $(".location").show();
            }else{
                $(".location").hide();
            }

            if(caption != ""){
                $(".addCaption").hide();
            }else{
                $(".addCaption").show();
            }

            toggleEditor('hide');
        }
    });
}

}
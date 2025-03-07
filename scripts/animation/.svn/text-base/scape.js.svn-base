function aniDisplayPostedScape(scapeData){
    $("#timelineFeed").prepend(scapeData);
    $("#timelineFeed div.scape").slideDown("slow");
    $("#noPosts").remove();
}

function aniDisplayScapeError(){
    $("#scapeError").fadeIn(2000,function(){
        $("#scapeError").fadeOut(2000);
    });
    aniUnlockPub(true);
}

function aniLockPub(){
    $("#editor").attr("contenteditable","false");
    $("#editor").css("background-color","#DDD");
    $("#postBtnLink").addClass("unavailable");
    $("#postBtn")[0].onclick=null;
}

function aniUnlockPub(preserve){

    if(!preserve){
        $("#editor").html("");
        $("#headingBox").val("");
        // Reset the counter
        $("span.charCounter").html("100").css("color", "#00ad00");
        $("#privacyList").html("");
    }

    $("#editor").attr("contenteditable","true");
    $("#editor").css("background-color","white");
    $("#postBtnLink").removeClass("unavailable");
    $("div.headingPanel").slideUp("fast");

    $('#postBtn')[0].onclick = function(){
        post();
    };

}



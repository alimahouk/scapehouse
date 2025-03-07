function ajaxSavePrivacy(id,show,hide){

    show = show.join("|");
    hide = hide.join("|");

     $.ajax({
        type:"POST",
        url:"/logged/ajax/privacysave",
        data: "id=" + id + "&show=" + encodeURIComponent(show)+ "&hide=" + encodeURIComponent(hide),
        error: function(){

        },
        success: function(data) {
            $("#privacyList").html("");
        }
    });


}

function ajaxGetPrivacy(id){

     $.ajax({
        type:"POST",
        url:"/logged/ajax/showprivacy",
        data: "id=" + id,
        error: function(){

        },
        success: function(data) {
            if(data.match(/typeHidden/)){
                $("#radio_hideFrom").attr("checked","checked");
            }else if(data.match(/typeShow/)){
                $("#radio_showTo").attr("checked","checked");
            }else{
                $("#radio_showTo").attr("checked","checked");
            }
            $("#privacyList").html(data);
        }
    });



}
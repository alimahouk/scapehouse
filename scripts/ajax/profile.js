/*function ajaxPm(ori,edited){

    if (ori != edited){
        $.ajax({
            type:"POST",
            url:"/logged/ajax/savepm",
            data: "pm=" + encodeURIComponent(edited)     
        });
}

function ajaxInfoDisplay(profileOwnerid,profileOwnerName){

            $.ajax({
                type:"POST",
                url:"/logged/ajax/infodisplay",
                data: "profileOwnerid=" + profileOwnerid + "&profileOwnerName=" + profileOwnerName,
                success: function(data) {

                    $("#infoDisplay").html(data);

                }
            });
 
}

function ajaxInfoEditor(profileOwnerid,profileOwnerName){
    
    if(!window.profileInfoEditor){

        if (profileOwnerid && profileOwnerName){
            $.ajax({
                type:"POST",
                url:"/logged/ajax/infoeditor",
                data: "profileOwnerid=" + profileOwnerid + "&profileOwnerName=" + profileOwnerName,
                success: function(data) {

                    $("#infoEditor").html(data);

                }
            });
        }
        window.profileInfoEditor = 1;
    }

    
} /*
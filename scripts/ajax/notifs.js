function ajaxDeleteNotif(id,jsonids){
     $.ajax({
        type:"POST",
        url:"/logged/ajax/deletenotif",
        data: "jsonids=" + jsonids,
        error: function(){

        },
        success: function() {
            $("#N"+id).remove();
        }
    });

}

function ajaxPeopleList(jsonids,type){
     $.ajax({
        type:"POST",
        url:"/logged/ajax/uiwpeoplelist",
        data: "jsonids=" + jsonids + "&type=" + type,
        error: function(){

        },
        success: function(html) {
            $("#peopleList").html(html).fadeIn();
        }
    });


}

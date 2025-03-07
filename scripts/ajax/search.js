//function ajaxSearch(query){
//
//    // Sends a request to the scape controller to update the timeline with the latest scape.
//    query = $.trim(query);
//    if(query.match(/^([a-zA-Z0-9@ _-]+)$/) && query != "" || query.match(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i)){
//        $.ajax({
//            type:"POST",
//            url:"/logged/ajax/search",
//            data:"query="+query,
//            success: function(data) {  alert(query);
//
//                if(data != ""){
//                    $("#privacySearchSuggestions").html(data).show();
//                    $("#privacySearchSuggestions li:first").addClass("selected");
//                }else{
//                    $("#privacySearchSuggestions").html("").hide();
//                }
//
//
//            }
//        });
//    }else{
//        $("#privacySearchSuggestions").html("").hide();
//    }
//}



//$(document).ready(function(){
//
//    var interval = 500;
//    var filterValue = "";
//
//$("#privacySearchInput").bind("keyup", logKeyPress);
//
//    function logKeyPress() {
//        var now = new Date().getTime();
//        var lastTime = this._keyPressedAt || now;
//        this._keyPressedAt = now;
//        if (!this._monitoringSearch) {
//            this._monitoringSearch = true;
//            var input = this;
//            window.setTimeout(
//                function() {
//                    search(input);
//                }, 0);
//        }
//    }
//    function search(input) {
//        var now = new Date().getTime();
//        var lastTime = input._keyPressedAt;
//        if ((now - lastTime) > interval) {
//            if (input.value != filterValue) {
//                filterValue = input.value;
//                ajaxSearch($("#privacySearchInput").val());
//            }
//            input._monitoringSearch = false;
//        }
//        else {
//            window.setTimeout(
//                function() {
//                    search(input);
//                }, 0);
//        }
//    }
//});
//
//$(document).keydown(function(e) {
//    var key = 0;
//
//    if (e === null) {
//        key = event.keyCode;
//    } else { // mozilla
//        key = e.which;
//    }
//
//    switch(key) {
//
//        case 38:
//            // Up
//            $("#privacySearchSuggestions li").each(function(){
//                if($(this).hasClass("selected")){
//                    curIndex = $(this).index();
//                    if(curIndex !== 0){
//                        $(this).removeClass("selected");
//                    }
//                }
//            });
//            newIndex = curIndex-1;
//            $("#privacySearchSuggestions li:eq("+newIndex+")").addClass("selected");
//
//            break;
//
//        case 40:
//            // Down
//            $("#privacySearchSuggestions li").each(function(){
//                if($(this).hasClass("selected")){
//                    curIndex = $(this).index();
//                    if ($("#privacySearchSuggestions li:last").index() != curIndex){
//                        $(this).removeClass("selected");
//                    }
//                }
//            });
//
//
//            newIndex = curIndex+1;
//            $("#privacySearchSuggestions li:eq("+newIndex+")").addClass("selected");
//
//            break;
//        case 13:
//
//            $("#privacySearchSuggestions li").each(function(){
//                if($(this).hasClass("selected")){
//                    curIndex = $(this).index();
//                    $(this).trigger('click');
//                }
//
//            });
//            break;
//    }
//});
//
//$(document).click(function(){
//    $("#privacySearchSuggestions").hide();
//});
//
//$("#privacySearchSuggestions li").live('click',function(){
//
//    $("#privacySearchInput").val("");
//
//    newElmid = $(this).attr('id');
//
//    unique = 1;
//    $("#privacyList div").each(function(){
//        if($(this).attr("id") == newElmid){
//            unique = false;
//        }
//    });
//    if(unique){
//        $('#privacyList').append('<div id="'+newElmid+'" class=\'item\'><span class=\'userName\'>'+$('span', this).html()+'</span><span onclick="$(\'#'+newElmid+'\').remove();" class=\'removeBtn\' title=\'Remove\'>X</span></div>');
//    }
//});

/* Scapehouse ajax control methods
 *
 */

(function ($) {

    var dsAjaxScape = {
      // Creates a scape
        create: function (scape,show,hide) {
            // Title had a bug, returns null for some reason =S, using $("#headingBox").val() to get title directly.
            if (scape.replace(/(<[^img].*?>)|(&nbsp;)/g, '').replace(/\s/g,"") != "" || $("#headingBox").val() != "") {

                show = show.join("|");
                hide = hide.join("|");
                // Sends a request to the scape controller to create a scape post.
                aniLockPub();

                $.ajax({
                    type: "POST",
                    url: "/logged/ajax/createscape",
                    data: "scape=" + encodeURIComponent(scape) + "&title=" + encodeURIComponent($("#headingBox").val()) + "&show=" + encodeURIComponent(show)+ "&hide=" + encodeURIComponent(hide),
                    error: function(){
                        aniDisplayScapeError();
                    },
                    success : function(scapeid){

                        if(scapeid.match(/^\d*$/) && scapeid != ""){

                            if (scape != "" || $("#headingBox").val() != ""){
                                getScape(scapeid);
                                aniUnlockPub();
                            }
                        }else{
                            aniDisplayScapeError();
                        }
                    }
                });
            }
        },
        get: function () {
        // ...
        }
    };

    var dsAjaxReply = {
        method1: function () {
        // ...
        },
        method2: function () {
        // ...
        }
    };

    var dsAjaxComment = {
        method1: function () {
        // ...
        },
        method2: function () {
        // ...
        }
    };

    var dsAjaxPhoto = {
        method1: function () {
        // ...
        },
        method2: function () {
        // ...
        }
    };

    var dsAjaxGeneral = {
        method1: function () {
        // ...
        },
        method2: function () {
        // ...
        }
    };
    
    // Expose to the global object
    window.dsAjaxScape = dsAjaxScape;
    window.dsAjaxReply = dsAjaxReply;
    window.dsAjaxComment = dsAjaxComment;
    window.dsAjaxPhoto = dsAjaxPhoto;
    window.dsAjaxGeneral = dsAjaxGeneral;

}(jQuery));
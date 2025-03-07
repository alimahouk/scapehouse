/** Scapehouse javascript library
 *  Author: Abdullah Khan
 */

(function ($) {

    var O = {
    
    //nav: UIAssets
        progressBar: '<img src="/graphics/en/UI/progress_bar_large_green.gif" style="margin-bottom: 15px;" class="progressBar" />',
        watchBtn: '<a class="UIButton large grey watch" title="Add this person to your watchlist"><span class="UIIcon px16 watchlist"></span><span class="UIButtonTxt">Watch</span></a>',
        watchStatusWatching: "<div class='watchStatus'><span class='UIIcon px16 tick'></span>Watching</div>",
        
        jelloonAffirm: '<div class="jelloon affirmative" id="jelloonAffirm" style="z-index:10;" onclick="$(this).fadeOut();">text</div>',
        jelloonNeg: '<div class="jelloon negative" id="jelloonNeg"  style="z-index:10;" onclick="$(this).fadeOut();">text</div>',
        
        scape : {
            
            // nav: O.scape.create();
            create: function(){

                // Get scape content and title
                var scape = O.processInput(scapePublisher.getEditorHTML(), "scape");
                
                var  title = $("#headingBox").val();
             
 
                    // check if scape actually contains content textual content or an image and check if title is also not empty
                    if (O.hasText(scape,true) || title != "") {

                        // Lock the publisher
                        O.animate.lockPub();
                        
                        // process tags ---
                        var tags = new Array();
                        
                        $("#privacyList .item").each(function(){
                            tags.push($(this).attr("id"));
                            });
                      
                        // Send a request to the scape controller to create a scape.
                        $.ajax({
                            type: "POST",
                            url: "/logged/ajax/createscape",
                            data: "scape=" + encodeURIComponent(scape) + "&title=" + encodeURIComponent(title) + "&tags=" + tags + "&private=" + $("#limitToTags").is(":checked"),
                            error: function(){
                                // Show scape postage error if request fails
                                O.animate.displayScapeError();
                            },
                            success : function(jsonResponce){
                                
                            var responce = $.parseJSON(jsonResponce);
                            var scapeid = responce.scapeid;                          

                                // Check is the scape id that is returned is numeric and is not empty
                                if(scapeid.match(/^\d*$/) && scapeid != ""){

                                    // check if the scape and title are not empty once again ( even thought I dont know why )
                                    if (scape != "" || title != ""){
                                        
                                        O.scape.get(scapeid);
                                        O.animate.unlockPub(false,"scape");
                                    }
                                }else{
                                    // Show scape postage error if request fails
                                    O.animate.displayScapeError();
                                }
                            }
                        });
                    }
            },

            // Gets a timeline scape with the givin id.
            // nav: O.scape.get();
            get: function(scapeid){
                // Send a request to the scape controller to get a timeline scape entry of for the given scape id
                $.ajax({
                    type:"POST",
                    url:"/logged/ajax/updatetimeline",
                    data:"scapeid="+scapeid,
                    success: function(scapeData) {

                        // display the scape that was fetched
                        O.animate.displayPostedScape(scapeData);

                    }
                });
            },
            // load more scape in the timeline based on an offset
            // nav: O.scape.load();
            load : function(location,userid,loadMore){

                // Send a request to the scape controller to load more scapes in the timeline.

                // check is last request returned no scape
                if(!O.noMoreScapes){
                    $("#loadMoreBtn").hide();
                    $("#timelineFeed").append(O.progressBar);

                    $.ajax({
                        type:"POST",
                        url:"/logged/ajax/displaytimeline",
                        data:"location=" + location + "&userid=" + userid+ "&loadMore=" + loadMore,
                        success: function(scapeData) {

                            if(scapeData == ""){
                                // Set the no more scapes variable.
                                O.noMoreScapes = true;
                                $("#loadMoreBtn").remove();
                            }
                            // display the loaded scapes
                            O.animate.displayNewLoadedScapes(scapeData);

                        }
                    });
                }
                
            },
            // edit a scape
            // nav: O.scape.edit();
            edit: function(){

                // get the id of the scape
                var id = $(".CGPost").attr("id").replace(/S/,"");
                // preserve the original content in a static variable the convert all emos in the content.
                if(!O.scapeContentOri){       
                    O.scapeContentOri = $("#scapeContent").html();
                    O.emoToText(id,"scape");
               }
 
                // this is sort of a switch, tells the function if the user is editing or done editing
                if(!O.edit){
                    O.edit = 1;
                }else{
                    O.edit = null;
                }
                // getting the content of the scape
                var  scapeContent = O.processInput($("#scapeContent").html(), "scape");
                var  scapeTitleOri = $("#postTitle").html();

                var  scapeTitle = $.trim($("#headingBox").val());
                var  editedScapeContent = O.processInput(scapeEditor.getEditorHTML(), "scape");

                // Work around for the placeholder text being sent as actual text
                if (scapeTitle == "Add a title..."){
                    scapeTitle = "";
                }
                if (O.edit){
                    // Edit
                    // Fire off the animations for editing
                    O.animate.editScape(scapeContent,scapeTitleOri,"edit");
                    // sneaky work around for IE placeholder problem.. placeholder did not show up.
                    $("#headingBox").focus();
                }else{
                    // Done Editing

                    // if the scape content is visibly empty then empty the scapes editor
                    if( editedScapeContent.replace(/(<[^img].*?>)|(&nbsp;)/ig, '').replace(/\s/g,"") == "" ) {
                        $("#editEditor").html("");
                    }

                    // these condition prevent a scape to be edited to emptyness, at any given time there should atleast be scape content
                    // or a scape title, both cannot be set to empty at the same time.
                    if( !O.hasText(editedScapeContent,true)&& scapeTitle != "" ||
                        O.hasText(editedScapeContent,true)&& scapeTitle == "" ||
                        O.hasText(editedScapeContent,true)&& scapeTitle != ""){
                        
                        //Ajax ---
                       
                        $.ajax({
                            type: "POST",
                            url: "/logged/ajax/editscape/format/xml",
                            data: "scape=" + encodeURIComponent(editedScapeContent) +"&title=" + encodeURIComponent(scapeTitle) +"&scapeid=" + id,
                            success : function(){ 

                                // Do the needed animations for the competion of the editing
                                O.animate.editScape(O.textToEmo(editedScapeContent),O.htmlspecialchars(scapeTitle),"done_change");

                                // Set the variable for the original scape content to null for the next use.
                                O.scapeContentOri = null;
                            }
                        });

                    }else{
                        // I guess this is dead code for the time being, the IF does nt really get till here...
                        // Probably going to use it some other time.
                        O.animate.editScape(O.scapeContentOri,scapeTitleOri,"done_nochange");
                        O.scapeContentOri = null;
                    }
                  
                }
            },
          
          deleteScape: function(elmid,scapesPage,scapebox){
            
                    $.ajax({
                       type:"POST",
                       url: "/logged/ajax/delete",
                       data: "elmid=" + elmid + "&idType=" + "scape" + "&scapebox=" + scapebox,
                       success: function(){
               
                           if(scapesPage){
                               var url = "/index";
                               $(location).attr("href",url);
                           }else{
                            $("#S" + elmid).slideUp("slow", function(){
                                $("#S" + elmid).remove();
                            });
                           }

                       }
                   });           
          }
          
        },

        reply : {
            // nav: O.reply.create();
            create: function(){
                
                var reply,scapeid;
                reply = O.processInput(replyPublisher.getEditorHTML(), "reply");
                scapeid = $(".CGPost").attr("id").replace(/S/,"");

                if(O.hasText(reply)){

                    O.animate.lockPub();

                    // Sends a request to create reply on a scape
                    $.ajax({
                        type: "POST",
                        url: "/logged/ajax/createreply",
                        data: "reply=" + encodeURIComponent(reply) + "&scapeid=" + scapeid,
                        error: function(){
                            O.animate.displayReplyError();
                            O.animate.unlockPub(true,"reply");
                        },
                        success : function(jsonResponce){
                            
                            var responce = $.parseJSON(jsonResponce);
                            var replyid = responce.replyid;
                            window.notifUrl = responce.notifUrl;
                            
                            if(O.isNumeric(replyid) && replyid != ""){

                                O.reply.get(replyid);
                                O.animate.displayReplyConfirmation();
                                O.animate.unlockPub(false,"reply");

                            }else{
                                O.animate.displayReplyError();
                                O.animate.unlockPub(true,"reply");
                            }
                        }
                    });
                }
            },
            // nav: O.reply.get();
            get: function(replyid){

                // Sends a request to get reply data of the given reply id
                $.ajax({
                    type:"POST",
                    url:"/logged/ajax/postedreply",
                    data: "replyid=" + replyid,
                    success: function(replyData) {

                        O.animate.displayPostedReply(replyData);
                       // O.notifMailer.send(window.notifUrl);

                    }
                });
                
            },
     
            // nav: O.reply.edit();
            edit: function(id){

                var replyContent;

                O.emoToText(id,"reply");
                
                // Set the contents of the editor for comparison
                // Get content depending on if full content is hidden
                
                if($("#R"+id+" .fullContent").length != 0){
                    replyContent = O.processInput($("#R"+id+" .fullContent").html(), "reply");
                    $("#R"+id+" .replyBodyTxt").html(replyContent);
                }else{
                    replyContent = O.processInput($("#R"+id+" .replyBodyTxt").html(),"reply");
                }

                
                if ($("#R"+id+" .editBtnLink").html() == "Edit"){
                
                    // Edit ----

                    O.publisher.create("RB"+id,"replyEditor");
                    O.animate.lockUI();
                    O.animate.unlockElm("#R"+id);
                    $("#R"+id+ " .likesAndDislikes").hide();
                    $("#R"+id+ " .showAllComments").hide();
                    
                    $("#R"+id+" .editBtnLink").html("Done editing");

                } else {
                    // Done Editing -----

                    $("#R"+id+" .editBtnLink").html("Edit");

                    //Get the edited content and clear the editor
                    var editedContent = O.processInput(replyEditor.getEditorHTML(),"reply");
                    replyEditor.destroy();

                    // put in edited cleaned HTML into the replybody
                    if(O.hasText(editedContent, false)){ // if has content put that in
                        $("#R"+id+" .replyBodyTxt").html(O.textToEmo(editedContent));
                    } else { // else take the orignal reply content and put that in, to avoid a blank reply.
                        $("#R"+id+" .replyBodyTxt").html(O.textToEmo(replyContent));
                    }
                                       
                    if (replyContent != editedContent && O.hasText(editedContent, false)){ // Ajax

                        $.ajax({
                            type:"POST",
                            url:"/logged/ajax/editreply",
                            data: "replyid=" + id + "&reply=" + encodeURIComponent(editedContent),
                            success: function() {     
                                // Back to normal z-index;
                                $("#R"+id).removeAttr("style");
                                $("#R"+id+ " .likesAndDislikes").show();
                                $("#R"+id+ " .showAllComments").show();
                                O.animate.unlockUI();
                            }
                        });
    
                    }else{ // Just unlock UI, no change took place

                        // Back to normal z-index;
                        $("#R"+id).removeAttr("style");
                        $("#R"+id+ " .likesAndDislikes").show();
                        $("#R"+id+ " .showAllComments").show();
                        O.animate.unlockUI();

                    }
                }

            },
            
            displayReplies: function(scapeid,limit,offset,batch){
                if ($("#batch"+batch).html() == "" || $("#batch"+batch).html() == null){
               
                       $(".replyBatch").hide();
                       $("#batch"+batch).html(O.progressBar).show();
                       $("#batch"+batch+ " .progressBar").attr("style","margin-top: 15px");
                       
                       $.ajax({
                           type:"POST",
                           url:"/logged/ajax/displayreplies",
                           data: "scapeid=" + scapeid + "&offset=" + offset + "&limit=" + limit,
                           success: function(data) {
               
                               $("#batch"+batch).html(data);
               
                               $("#replies div.CGReply").css({
                                   display: 'block'
                               });

                           }
                       });
               
                   }else{
                       $(".replyBatch").hide();
                       $("#batch"+batch).show();
               
                   }               
            },
            
            deleteReply: function(elmid){
                
                $.ajax({
                       type:"POST",
                       url: "/logged/ajax/delete",
                       data: "elmid=" + elmid + "&idType=" + "reply",
                       success: function(){
               
                            $("#R" + elmid).slideUp("slow", function(){
                                $("#R" + elmid).remove();
                            });

                       }
                   });      
            }
        },
        
        photo:{
            makeCurrent: function(photoid){
                $.ajax({
                   type:"POST",
                   url: "/logged/ajax/makecurrent",
                   data: "photoid=" + photoid,
                   success: function(){
                    O.animate.jelloon("This photo is now your profile picture.",true);
                   }
               });               
            },
            edit: function(photoid) {
                if (photoid != null) {
                    $("#editCaption").attr("disabled", "true");
                    $("#editCaption").css("background-color", "#DDD");

                    $("#editLocation").attr("disabled", "true");
                    $("#editLocation").css("background-color", "#DDD");
                    
                    var caption = $("#editCaption").val();
                    var location = $("#editLocation").val();
                    
                    $.ajax({
                        type: "POST",
                        url: "/logged/ajax/editphotodetails",
                        data: "photoid=" + photoid + "&caption=" + encodeURIComponent(caption) + "&location=" + encodeURIComponent(location),
                        success: function() {

                            $("#editCaption").removeAttr("disabled");
                            $("#editCaption").css("background-color", "white");
                            $("#caption").html(O.htmlspecialchars($("#editCaption").val()).replace(/\n|\r/ig,"<br />"));

                            $("#editLocation").removeAttr("disabled");
                            $("#editLocation").css("background-color", "white");
                            $("#location").html(O.htmlspecialchars($("#editLocation").val()));

                            if (location != "") {
                                $(".location").show();
                            } else {
                                $(".location").hide();
                            }

                            if (caption != "") {
                                $(".addCaption").hide();
                            } else {
                                $(".addCaption").show();
                            }

                            O.animate.photo.toggleEditor('hide');
                        }
                    });
                }
            },
            
            deletePhoto: function(elmid){
                
                    $.ajax({
                       type:"POST",
                       url: "/logged/ajax/delete",
                       data: "elmid=" + elmid + "&idType=" + "photo",
                       success: function(){
                        
                        O.animate.jelloon("This photo has been deleted.",true);
                        
                        setTimeout(function(){
                         var url = "/"+$("#session").attr("username")+"/gallery";
                         $(location).attr("href",url);},3000);

                       }
                   });                   
            }
        },
        
        comment: {
                                
            // nav: O.comment.create();
            create: function(elmid,location){

                var idType,id,idAndType;
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;

                var comment = O.processInput(commentPublisher.getEditorHTML(), "comment");

                if(O.hasText(comment) && elmid != null && idType != null){ 
                
                if(location == "commentWindow"){
                    $(".UIWindowContents .post").addClass("unavailable");
                    $(".UIWindowContents .post")[0].onclick=null;
                }else{
                   O.animate.lockMiniPub(elmid);
                }
                    
                    $.ajax({
                        type:"POST",
                        url:"/logged/ajax/createcomment",
                        data: "comment=" + encodeURIComponent(comment) + "&elmid=" + id + "&idType=" + idType,
                        error: function(){
                            O.animate.unlockMiniPub(true,elmid);
                        },
                        success: function(jsonResponce) {
                            
                            var responce = $.parseJSON(jsonResponce);
                            var commentid = responce.commentid;
                            

                            if (location == "commentWindow"){
                                
                            commentPublisher.setEditorHTML("");
                                
                            if(location == "commentWindow"){
                                $(".UIWindowContents .post").removeClass("unavailable");
                                $(".UIWindowContents .post")[0].onclick = function() {
                                    O.comment.create(elmid,"commentWindow");
                                };
                            } 
                                O.comment.get(commentid);
                            } else {
                                // Animation ----
                                O.animate.commentCount(elmid);
                            }
                            
                            O.animate.unlockMiniPub(elmid);
                        }
                    });
                }
            },
            
            get: function(commentid){
                $.ajax({
                    type:"POST",
                    url:"/logged/ajax/postedcomment",
                    data: "commentid=" + commentid,
                    success: function(data) {
            
                        $("#comments").append(data);
            
                    }
                });                
            },

            // nav: O.comment.edit();
            edit: function(elmid){ 

                var idType,id,idAndType,content,editedContent;
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;

                O.emoToText(id,"comment");

                content = O.processInput($("#CB"+id).html(), "comment");
                $("#CB"+id).html(content);
                

                if($("#C"+id+" .editBtn").html().match(/<a>Edit<\/a>/i)){
                    // Edit
                    O.publisher.create("CB"+id,"commentEditor");
                    $("#C"+id+" .editBtn").html("<a>Done editing</a>");

                // Lock window
                $(".sectionOverlay").show();
                // Unlock comment being edited
                O.animate.unlockElm("#C"+id);
                }else{
                    // Done Editing
                    $("#C"+id+" .editBtn").html("<a>Edit</a>");
                    editedContent = O.processInput(commentEditor.getEditorHTML(), "comment");
                    commentEditor.destroy();

                    $("#CB"+id).html(O.textToEmo(editedContent));                  
                    
                    if(editedContent != content && O.hasText(editedContent, false)){

                        $.ajax({
                            type:"POST",
                            url:"/logged/ajax/editcomment",
                            data: "comment=" + encodeURIComponent(editedContent) + "&commentid=" + id,
                            success: function(data) {

                            // unlock window
                            $(".sectionOverlay").hide();
                            // lock comment being edited
                            $("#C"+id).removeAttr("style"); 
                            }
                        });

                    }else{

                    // unlock window
                    $(".sectionOverlay").hide();
                    // lock comment being edited
                    $("#C"+id).removeAttr("style"); 
                           
                }
                }                   
            },
            
            deleteComment: function(elmid){
                
                $.ajax({
                       type:"POST",
                       url: "/logged/ajax/delete",
                       data: "elmid=" + elmid + "&idType=" + "comment",
                       success: function(){
               
                            $("#C" + elmid).slideUp("slow", function(){
                                $("#C" + elmid).remove();
                                $(".sectionOverlay").hide();
                            });

                       }
                   });            
            },

            // nav: O.comment.getComments();
            // Needs to be fixed, only being used for reply window at the moment
            getAllOnElm: function(elmid){
                
                var idType,id,idAndType;
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;

                if(elmid != null && idType != null){

                    O.animate.displayReplyWindow("scape");

                     O.UIWindow.reply(id);

                }
            }
        },
        
        note: {
            create: function(){
                if($.trim($("#notepad").val()) != ""){
                    
                $("#pinNote").addClass("unavailable");
                $("#pinNote")[0].onclick=null;
                $("#pinNoteSpinner").show();
                
                var color = $(".color.selected").attr("color");
                $.ajax({

                       type:"POST",
                       url: "/logged/ajax/createnote",
                       data: "reciverid=" + $("#owner").attr("userid") + "&content=" + encodeURIComponent($("#notepad").val()) + "&color=" + color,
                       success: function(id){
               
                        $("#pinNote")[0].onclick= function(){O.note.create()};
                         $("#pinNote").removeClass("unavailable");
                         $("#pinNoteSpinner").hide();
                         $("#notepad").val("");
                         $("#noteCharCounter").html("300");
                         $("#noteCharCounter").removeAttr("style");
                         $(".noNotes").remove();
                         O.note.get(id);
                       }
                   });                
            }
            
        },
        
        load : function(){

                if(!O.noMoreNotes){
                    $("#loadMoreBtnNotes").hide();
                    $("#notes").append(O.progressBar);
                    
                    if (!O.noteBatch){
                        O.noteBatch = 0;
                    }
                    O.noteBatch++;
                    loadMore = 15*O.noteBatch;

                    $.ajax({
                        type:"POST",
                        url:"/logged/ajax/getnotesbyreciverid",
                        data:"userid=" + $("#owner").attr("userid")+ "&loadMore=" + loadMore,
                        success: function(noteData) {

                            if(noteData == ""){
                                // Set the no more scapes variable.
                                O.noMoreNotes = true;
                                $("#loadMoreBtnNotes").remove();
                            }
                            $("#notes").append(noteData);
                            $("#notes .progressBar").remove();
                            $("#loadMoreBtnNotes").show();

                        }
                    });
                }
                
            },
        
          get: function(id){

                $.ajax({
                       type:"POST",
                       url: "/logged/ajax/getnote",
                       data: "id=" + id,
                       success: function(newNote){
                        
                        $("#notes").prepend(newNote);
                        $("#M"+id).hide().fadeIn();
                                                
                       }
                   });
                
            },
            
            deleteNote: function(elmid){
                
                var idType,id,idAndType;
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;
                
                $.ajax({
                       type:"POST",
                       url: "/logged/ajax/delete",
                       data: "elmid=" + id + "&idType=" + idType,
                       success: function(){ 
                        $("#"+elmid).fadeOut(function(){$("#"+elmid).remove();});
                        
                       }
                   });    
            },
            
        getReply: function(id,elmid){ 
            
                 $.ajax({
                       type:"POST",
                       url: "/logged/ajax/getnotereply",
                       data: "id=" + id,
                       success: function(newNoteReply){
                        alert(newNoteReply);
                         $("#"+elmid+ " .noteReplyContainer").append(newNoteReply);
                       }
                   });   
            },
        
        createReply: function(elmid){
            
            var content = $("#"+elmid+" textarea").val();
            var noteid = elmid.replace("MR","");

                 $.ajax({
                       type:"POST",
                       url: "/logged/ajax/createnotereply",
                       data: "noteid=" + noteid + "&content=" + content,
                       success: function(id){
                        
                        O.note.getReply(id,elmid);
                        
                       }
                   });   
            }  
        },

        like: {
            // nav: O.like.create();
            
            create: function(elmid){

                var idType,id,idAndType;
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;

                // Send a request to create a dislike on the chosen element.
                
                // show spinner
                $("#"+elmid+ " .spinner").show();
                
                $.ajax({
                    type:"POST",
                    url: "/logged/ajax/likesdislikes",
                    data: "elmid=" + id + "&idType=" + idType + "&l=1",
                    success: function(html){

                        O.animate.like(elmid,html);
                        //Hide spinner
                        $("#"+elmid+ " .spinner").hide();
                       
                    }
                });
            }
        },
        // nav: O.dislike.create();
        dislike: {
            create: function(elmid){
                
                // Sends a request to create a dislike on the chosen element.

                var idType,id,idAndType;
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;
                
                //Show spinner
                $("#"+elmid+ " .spinner").show();
                
                $.ajax({
                    type:"POST",
                    url: "/logged/ajax/likesdislikes",
                    data: "elmid=" + id + "&idType=" + idType + "&d=1",
                    success: function(html){

                        O.animate.dislike(elmid,html);
                        
                        //Hide spinner
                       $("#"+elmid+ " .spinner").hide();

                    }
                });
            }
        },
        
        watch: {
            create: function(btn,userid){
                $.ajax({
                    type:"POST",
                    url: "/logged/ajax/watch",
                    data: "targetid=" + userid,
                    success: function(){
                        $(btn).replaceWith(O.watchStatusWatching);
                    }
                });                
            },
            
            unwatch: function(btn,watchid,async){
                
                if(async == false){
                   async = false;
                }else{
                  async = true; 
                }
                $.ajax({
                   type:"POST",
                   url: "/logged/ajax/ignore",
                   async: async,
                   data: "watchid=" + watchid,
                   success: function(){
                     $(btn).parents(".watchlistEntry").slideUp();
                   }
               });               
            }
        },
        
        //nav: O.notifMailer.send();
        notifMailer: {
            send: function(notifUrl){
                
                $.ajax({
                    type:"GET",
                    url: notifUrl
                });
                
            }
        },
        
        notif: {
            
            updateBadge: function(){
            
                 $.ajax({
                    type: "POST",
                    url: "/logged/ajax/updatebadge",
                    error: function() {

                 },
                    success: function(count) {
                    if(count != 0 && count != $(".badgeInnerWrapper").html()){
                       $("#notificationBadge").fadeOut(function(){$(".badgeInnerWrapper").html(count);});
                       $("#notificationBadge").fadeIn();
                       
                       
                    }
                }
                });
                                
            },
            
            deleteNotif: function(id,jsonids) {
                $.ajax({
                    type: "POST",
                    url: "/logged/ajax/deletenotif",
                    data: "jsonids=" + jsonids,
                    error: function() {

                 },
                    success: function() {
                        $("#N" + id).slideUp(function(){$("#N" + id).remove();});
                    }
                });
            }
        },


        // nav: O.searchSuggestions.create();
        searchSuggestions: { 
            create: function(inputid,type){ 
                $(document).ready(function(){
    
                    var interval = 500;
                    var filterValue = "";
    
                    $("#"+inputid).bind("keyup", logKeyPress);

                    function logKeyPress() {
                        var now = new Date().getTime();
                        var lastTime = this._keyPressedAt || now;
                        this._keyPressedAt = now;
                        if (!this._monitoringSearch) {
                            this._monitoringSearch = true;
                            var input = this;
                            window.setTimeout(
                                function() { 
                                    search(input);
                                }, 0);
                        }
                    }
                    function search(input) {
                        var now = new Date().getTime();
                        var lastTime = input._keyPressedAt;
                        if ((now - lastTime) > interval) {
                            if (input.value != filterValue) {
                                filterValue = input.value;
                                // Ajax call for results	
                                // Sends a request to the scape controller to update the timeline with the latest scape.
                                var query = $.trim($("#"+inputid).val());
                                if(query.match(/^([a-zA-Z0-9@ _-]+)$/) && query != "" || query.match(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i)){
                                    $.ajax({
                                        type:"POST",
                                        url:"/logged/ajax/search",
                                        data:"query="+query+"&type="+type,
                                        success: function(data) {

                                            if(data != ""){
                                                $("#"+inputid+"Suggestions").html(data).show();
                                                $("#"+inputid+"Suggestions li:first").addClass("selected");
                                            }else{
                                                $("#"+inputid+"Suggestions").html("").hide();
                                            }


                                        }
                                    });
                                }else{
                                    $("#"+inputid+"Suggestions").html("").hide();
                                }

                            // Ajax call for results
 
                            }
                            input._monitoringSearch = false;
                        }
                        else {
                            window.setTimeout(
                                function() { 
                                    search(input);
                                }, 0);
                        }
                    }
                });

                $(document).keydown(function(e) {
                    var key = 0;
    
                    if (e === null) {
                        key = event.keyCode;
                    } else { // mozilla
                        key = e.which;
                    }

                    switch(key) {

                        case 38:
                            // Up
                            $("#"+inputid+"Suggestions li").each(function(){
                                if($(this).hasClass("selected")){
                                    curIndex = $(this).index();
                                    if(curIndex !== 0){
                                        $(this).removeClass("selected");
                                    }
                                }
                            });
                            newIndex = curIndex-1;
                            $("#"+inputid+"Suggestions li:eq("+newIndex+")").addClass("selected");
            
                            break;

                        case 40:
                            // Down
                            $("#"+inputid+"Suggestions li").each(function(){
                                if($(this).hasClass("selected")){
                                    curIndex = $(this).index();
                                    if ($("#"+inputid+"Suggestions li:last").index() != curIndex){
                                        $(this).removeClass("selected");
                                    }
                                }
                            });
            

                            newIndex = curIndex+1;
                            $("#"+inputid+"Suggestions li:eq("+newIndex+")").addClass("selected");

                            break;
                        case 13:

                            $("#"+inputid+"Suggestions li").each(function(){
                                if($(this).hasClass("selected")){
                                    curIndex = $(this).index();
                                    $(this).trigger('click');
                                    $("#"+inputid).val("");
                                }

                            });
                            break;
                    }
                });

                $(document).click(function(){
                    $("#"+inputid+"Suggestions").hide();
                });

                $("#"+inputid+"Suggestions li").live('click',function(){

                    $("#"+inputid+"Input").val("");

                    newElmid = $(this).attr('id');

                    unique = 1;
                    $("#privacyList div").each(function(){
                        if($(this).attr("id") == newElmid){
                            unique = false;
                        }
                    });
                    if(unique){
                        $('#privacyList').append('<div id="'+newElmid+'" class=\'item\'><span class=\'userName\'>'+$('span', this).html()+'</span><span onclick="$(\'#'+newElmid+'\').remove();" class=\'removeBtn\' title=\'Remove\'>X</span></div>');
                    }
                });

            }
        },

        UIWindow: {
            // nav: O.UIWindow.reply();
            reply: function(replyid){
                $.ajax({
                    type:"POST",
                    url:"/logged/ajax/uiwreply",
                    data: "replyid=" + replyid,
                    error: function(){

                    },
                    success: function(html) {
                        O.windowManager.setWindowContents("replyWindow",html);
                    }
                });
            },
            
            comments: function(elmid){
                
                O.windowManager.setWindowContents("commentWindow", O.progressBar);
                $("#commentWindow .progressBar").attr("style","margin-top: 180px;")
                
                var idType, id, idAndType;
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;

                $.ajax({
                    type: "POST",
                    url: "/logged/ajax/uiwcomments",
                    data: "id=" + id + "&idType=" + idType,
                    success: function(html) {
                        O.windowManager.setWindowContents("commentWindow", html);
                    }
                });
            },
        
            // nav: O.UIWindow.listUsers();
            listUsers: function(jsonids,type){
                O.animate.displayListUsersWindow(type);
                $.ajax({
                    type:"POST",
                    url:"/logged/ajax/uiwlistusers",
                    data: "jsonids=" + jsonids + "&type=" + type,
                    error: function(){

                    },
                    success: function(html) {
                        O.windowManager.setWindowContents("listUsersWindow",html);
                    }
                });
            },
            
            feedback: function() {
                $.ajax({
                    url: "/logged/ajax/uiwfeedback",
                    error: function() {

                  },
                    success: function(html) {
                        $(".UIWindow").hide();
                        $("#feedbackWindowContainer").html(html).show();
                        O.animate.lockUI();
                    }
                });
            },
            feedbackSubmit: function(type,content,thumbsup,thumbsdown) {
                $.ajax({
                    type: "POST",
                    url: "/logged/ajax/submitfeedback",
                    data: "type=" + type + "&content=" + content + "&thumbsup=" + thumbsup + "&thumbsdown=" + thumbsdown,
                    error: function() {

                   },
                     success: function() {
                   }
                });
                O.animate.jelloon("Thanks again! ...Your feedback has been submitted.",true);
              }
       },

        // sets up the counter for the heading/title in the home page and scape page based on arguments
        headingCounter :{
            // nav: O.headingCounter.create();
            create : function(type){ 
                
                // --- Heading
                $('div.heading').hide();
                $("#insertTitleBtnContainer").click(function() {
                    $("div.heading").slideDown("fast");
                    $("div.heading textarea").focus();
                });

                $("div.heading span.cancelBtn").click(function() {
                    $("div.heading").slideUp("fast");

                    // Empty the textarea
                    $("#headingBox").val("");

                    // Reset the counter
                    $("span.charCounter").html("100").css("color", "#00ad00");
                });

                var whenkeydown = function(max_length)
                {
                    $("#headingBox").unbind().keyup(function()
                    {
                        // Check if the appropriate text area is being typed into
                        if(document.activeElement.id === "headingBox")
                        {
                            // Get the data in the field
                            var text = $(this).val();

                            // Set number of characters
                            var numofchars = text.length;

                            //set the chars left
                            var chars_left = max_length - numofchars;

                            // Check if we are still within our maximum number of characters or not
                            if(numofchars <= max_length)
                            {
                                // Set the length of the text into the counter span
                                $("span.charCounter").html("").html(chars_left).css("color", "#000");
                            }
                            else
                            {
                                // Style counter in red
                                $("span.charCounter").html("").html(chars_left).css("color", "#f00");
                            }
                        }
                    });
                };

                // Set max length
                var max_length = 100;
                var text = $('#headingBox').val();
                var numofchars = text.length;
                var charsLeft = 100 - numofchars;

                // Load in max characters when page loads
                $("span.charCounter").html(charsLeft);

                // Listen for key press events
                whenkeydown(max_length);

                // scape page stuff
                if(type === "scape"){

                    $(".postTitleEditor").focus(function() {
                        $("span.deleteBtn.heading").fadeIn("fast");
                        $("span.charCounter").fadeIn("fast");
                        //$(".postTitleEditor").height("40px");
                        if($(".postTitleEditor").val() == "" || $(".postTitleEditor").val() == "Add a title...") {
                            $(".postTitleEditor").html("").css("color", "#333");
                             
                        }
                    });

                    $(".postTitleEditor").blur(function() {
                        $("span.deleteBtn.heading").fadeOut("fast");
                        $("span.charCounter").fadeOut("fast");
                       // $(".postTitleEditor").height("20px");
                        if($(this).val() == "") {
                            $(".postTitleEditor").html("Add a title...").css("color", "#999");
                            
                        }
                    });

                    $("span.deleteBtn.heading").bind("click",function(){
                        $(".postTitleEditor").html("");
                        $("span.charCounter").html("100");
                    });

                    if($("#headingBox").val() == "") {
                        $(".postTitleEditor").html("Add a title...").css("color", "#999");
                    }else{
                        $(".postTitleEditor").css("color", "#333");
                    }
                    
                }
                
                
            }
            
        },

        // signup
        signup : {
            
            bootloader: function(){
                $("#firstname").bind("blur",function(){
                    O.signup.validateFirstname();
                });
                $("#lastname").bind("blur",function(){
                    O.signup.validateLastname();
                });
                $("#email").bind("blur",function(){
                    O.signup.validateEmail();
                });
                $("#username").bind("blur",function(){
                    O.signup.validateUsername();
                });
                $("#password").bind("blur",function(){
                    O.signup.validatePassword();
                });
                
            },

            validateFirstname: function(){

                var firstname = $.trim($("#firstname").val());
                
                if (firstname.match(/^[a-zA-Z\s]{2,20}$/)){
                    
                    $("#firstnameField .field").removeClass("negative").addClass("affirmative");
                    $("#firstnameField .actionFeedback").html("OK").removeClass("negative").addClass("positive").show().attr("style","display: inline block;");
                    $("#firstnameError").slideUp("fast");

                    O.validFirstname = true;
                    return true;

                } else {
                    if(firstname == ""){
                         
                        $("#firstnameField .field").removeClass("negative").removeClass("affirmative");
                        $("#firstnameField .actionFeedback").html("").hide();
                        $("#firstnameError").slideUp("fast");

                        O.validFirstname  = false;
                        return false;
                    }else{
                                          
                        $("#firstnameField .field").removeClass("affirmative").addClass("negative");
                        $("#firstnameField .actionFeedback").html("Invalid!").removeClass("positive").addClass("negative").attr("style","display: inline block;");
                        $("#firstnameError").slideDown("fast");
                        
                        O.validFirstname  = false;
                        return false;
                    }

                }
            },

            validateLastname: function(){

                var lastname = $.trim($("#lastname").val());

                if (lastname.match(/^[a-zA-Z\s]{2,20}$/)){

                    $("#lastnameField .field").removeClass("negative").addClass("affirmative");
                    $("#lastnameField .actionFeedback").html("OK").removeClass("negative").addClass("positive").show();
                    $("#lastnameError").slideUp("fast");

                    O.validLastname  = true;
                    return true;

                } else {
                    
                    if(lastname == ""){

                        $("#lastnameField .field").removeClass("negative").removeClass("affirmative");
                        $("#lastnameField .actionFeedback").html("").hide();
                        $("#lastnameError").slideUp("fast");

                        O.validLastname  = false;
                        return false;
                    }else{
                        $("#lastnameField .field").removeClass("affirmative").addClass("negative");
                        $("#lastnameField .actionFeedback").html("Invalid!").removeClass("positive").addClass("negative").show();
                        $("#lastnameError").slideDown("fast");

                        O.validLastname  = false;
                        return false;
                    }
                }
            },

            validateEmail: function(){

                var email = $.trim($("#email").val());
                
                if (email.match(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i)){

                    $.ajax({
                        type:"POST",
                        url:"/logged/ajax/checkemail",
                        data: "email=" + email,
                        async: false,
                        success: function(data) {

                            if(data == "true"){
                                
                                $("#emailField .field").removeClass("affirmative").addClass("negative");
                                $("#emailField .actionFeedback").html("Exists!").removeClass("positive").addClass("negative").show();
                                $("#emailError").slideUp("fast");

                                O.validEmail = false;
                                return false;
                            }else{
                     
                                $("#emailField .field").removeClass("negative").addClass("affirmative");
                                $("#emailField .actionFeedback").html("OK").removeClass("negative").addClass("positive").show();
                                $("#emailError").slideUp("fast");

                                O.validEmail = true;
                                return true;
                            }

                        }
                    });

                } else {
                    if (email == ""){

                        $("#emailField .field").removeClass("negative").removeClass("affirmative");
                        $("#emailField .actionFeedback").html("").hide();
                        $("#emailError").slideUp("fast");

                        O.validEmail  = false;
                        return false;

                    }else{
                        $("#emailField .field").removeClass("affirmative").addClass("negative");
                        $("#emailField .actionFeedback").html("Invalid!").removeClass("positive").addClass("negative").show();
                        $("#emailError").slideDown("fast");
                    }
                    O.validEmail = false;
                    return false;
                }
            },

            validateUsername: function(){

                var username = $.trim($("#username").val()); 

                if (username.match(/^[a-zA-Z0-9._-]{2,20}$/)){

                    $.ajax({
                        type:"POST",
                        url:"/logged/ajax/checkusername",
                        data: "username=" + username,
                        async: false,
                        success: function(data) {

                            if(data == "true"){
                                
                                $("#usernameField .field").removeClass("affirmative").addClass("negative");
                                $("#usernameField .actionFeedback").html("Taken!").removeClass("positive").addClass("negative").show();
                                $("#usernameError").slideUp("fast");

                                O.validUsername = false;
                                return false;

                            }else{
                           
                                $("#usernameField .field").removeClass("negative").addClass("affirmative");
                                $("#usernameField .actionFeedback").html("OK").removeClass("negative").addClass("positive").show();
                                $("#usernameError").slideUp("fast");

                                O.validUsername = true;
                                return true;
                            }

                        }
                    });

                } else {
                    if(username == ""){

                        $("#usernameField .field").removeClass("negative").removeClass("affirmative");
                        $("#usernameField .actionFeedback").html("").hide();
                        $("#usernameError").slideUp("fast");

                        O.validUsername  = false;
                        return false;

                    }else{

                        $("#usernameField .field").removeClass("affirmative").addClass("negative");
                        $("#usernameField .actionFeedback").html("Invalid!").removeClass("positive").addClass("negative").show();
                        $("#usernameError").slideDown("fast");

                        O.validUsername  = false;
                        return false;
                    }
                }
            },

            validatePassword: function(){
                
                var password = $.trim($("#password").val());

                if (password.match(/^.{6,}$/)){

                    $("#passwordField .field").removeClass("negative").addClass("affirmative");
                    $("#passwordField .actionFeedback").html("OK").removeClass("negative").addClass("positive").show();
                    $("#passwordError").slideUp("fast");

                    O.validPassword = true;
                    return true;

                } else {

                    if(password == ""){
                        
                        $("#passwordField .field").removeClass("negative").removeClass("affirmative");
                        $("#passwordField .actionFeedback").html("").hide();
                        $("#passwordError").slideUp("fast");

                        O.validPassword = false;
                        return false;

                    }else{ 
                        
                        $("#passwordField .field").removeClass("affirmative").addClass("negative");
                        $("#passwordField .actionFeedback").html("Invalid!").removeClass("positive").addClass("negative").show();
                        $("#passwordError").slideDown("fast");

                        O.validPassword = false;
                        return false;
                    }

                }
            },

            submit: function(){
                var validCaptcha = $("#recaptcha_response_field").val();
                
                if(validCaptcha == ""){
                    O.validCaptcha = false;
                }else{
                    O.validCaptcha = true;
                }

                O.signup.validateFirstname();
                O.signup.validateLastname();
                O.signup.validateEmail();
                O.signup.validateUsername();
                O.signup.validatePassword();
                

                if( O.validFirstname &&
                    O.validLastname &&
                    O.validEmail &&
                    O.validUsername &&
                    O.validPassword &&
                    O.validCaptcha){

                    $("#signupForm").submit();
                    
                }else{
                    $("#errorSpace").slideDown("fast");
                }
            }
        },
        // home page stuff
        homePage : {
            
            // Prepare the homepage for use
            // nav: O.homePage.bootloader();
            bootloader: function(){

                O.publisher.create("pubTarget","scapePublisher");

                // on clicking the sites body adds the place holder text back if the publsiher not populated with content

                $('#pubPlaceholder').click(function(){
                    $("div.heading").slideUp("fast");
                    $('#lowerPanel').show();         
                });

                $('body').click(function() {
                    
                   // $('#lowerPanel').hide();
                    var data = O.processInput(scapePublisher.getEditorHTML(), "scape");
                    if ($.trim(data) != ""){
                        $("#pubPlaceholder").html(data);
                    }else{
                        $("#pubPlaceholder").html("<span style=\"color: #777\">Express yourself...</span>");
                    }
                    $(".yui-editor-editable-container").attr("style","height:70px");
                   // $('#pubTargetHolder').hide();
                   // $('#pubPlaceholder').show();
                   // $('div.bubbleArrow').css("background", "transparent url('/graphics/en/sprites/publisher_arrows.png') no-repeat 0 0");

                });

                $('#publisher').click(function(event){
                    event.stopPropagation();
                });

                // initialize heading counter for homepage
                O.headingCounter.create("home");
    
                // Load more button config -------
                $("#loadMoreBtn").click(function(){

                    if(!O.mult){
                        O.mult = 0;
                    }
                    O.mult++;
                    offset = 10*O.mult + 10;
                    O.scape.load("home",null,offset);
                });

                // Scroll to load config -----
                $(window).scroll(function(){
                    if  ($(window).scrollTop()+40 > $(document).height() - $(window).height()){
                        if(!O.loadMore){
                            O.scape.load("home",null,"10");
                            O.loadMore = true;
                        }
                    }
                });

                // initialize the emo table in the publisher
                O.emoticonTable();

                $(".scape").live("mouseenter",function() {
                    $(".readMore",this).stop(true, true).fadeIn("fast");
                });
                $(".scape").live("mouseleave",function() {
                    $(".readMore",this).stop(true, true).fadeOut("fast");
                });

            }
        },

        // scapes page stuff
        scapePage: {
            // nav: O.scapePage.bootloader();
            bootloader: function(replyBatches,replyCount){

                // declare variables
                var scapeid,value,offset,limit,replyRangeUp,replRangeDown,content;

                // Initialize all the publishers on the page. RB58
                O.publisher.create("replyPubTarget","replyPublisher");
                O.publisher.create("scapeEditor","scapeEditor");

                $('#pubPlaceholder').click(function(){
                  //  $('#lowerPanel').show();
                  //  $('#replyPubTargetHolder').show();
                  //  $('#pubPlaceholder').hide();
                    $('div.bubbleArrow').css("background", "transparent url('/graphics/en/sprites/publisher_arrows.png') no-repeat 0 -46px");
                });

                $('body').click(function() {

                   // $('#lowerPanel').hide();
                    var data = O.processInput(replyPublisher.getEditorHTML(), "reply");
                    if ($.trim(data) != ""){
                        $("#pubPlaceholder").html(data);
                    }else{
                        $("#pubPlaceholder").html("");
                    }
                    $("#publisher .yui-editor-editable-container").attr("style","height:70px");
                   // $('#replyPubTargetHolder').hide();
                  //  $('#pubPlaceholder').show();
                  //  $('div.bubbleArrow').css("background", "transparent url('/graphics/en/sprites/publisher_arrows.png') no-repeat 0 0");

                });

                $('#publisher').click(function(event){
                    event.stopPropagation();
                });
                
                scapeid = $(".CGPost").attr("id").replace(/S/,"");

                
                // initialize heading counter for scape page
                O.headingCounter.create("scape");
                // initialize the emo table in the publisher
                O.emoticonTable();

                // Initialize the slider

                $("#slider").slider({
                    max: replyBatches,

                    value: replyBatches,

                    stop: function() {

                        // Capture value of the slider
                        value = $( "#slider" ).slider( "option", "value");

                        // This line allows to flip the values passed by the slider
                        value = replyBatches - value;

                        // Incrementing the value of value variable by one to overcome the 0 value input
                        value++;

                        // setting the offset to pass into mysql
                        offset = 10 * (value - 1);
                        // Init limit for passing to mysql
                        limit = 10;
                        // decement the value of value variable by one (now back to orignal value) for use in comparison
                        value--;
                        // setting the upper range of the text feed back under the slider
                        replyRangeUp = replyCount-10-offset+1;
                        // setting the lower range of the text feed back under the slider
                        replRangeDown = replyCount-offset;


                        if( value == replyBatches){
                            limit = replyCount - offset;
                            offset = replyCount-10;

                            replyRangeUp = 1;
                            replRangeDown = limit;
                        }
                        // Incrementing the value of value variable by one again to overcome the 0 value input and for passing to
                        // the ajax function
                        value++;
                        O.reply.displayReplies(scapeid,limit,replyCount-10-offset,value);

                        $("#replyCounter").html('Showing replies <span class="lowerLimit">'+replyRangeUp+'</span> to <span class="upperLimit">'+replRangeDown+'</span><span class="totalReplies"> of '+replyCount+'</span>');
                        $("#currentlyShowing").html(replyRangeUp+' to '+replRangeDown);
                    }
                });

            // slider end --
             
            }
        },
		
        // profile page stuff
        profilePage: {
            bootloader: function(){
                $("#pmEdit").click(function(){
                    var input;
                    
                    O.orignalPm = $.trim($("#pmSpan").html());
                    
                    if(O.orignalPm == "A little personal message..."){
                        input = "";
                    }else{
                        input = O.orignalPm;
                    }
                    
                    $("#pmSpan").html("<textarea id='pmEditor' maxlength='400'>"+input+"</textarea>");
                    $("#pmEditor").focus();
                    $(this).hide();
                    
                });

                $("#pmEditor").live("blur",function(){
                    if($.trim($(this).val()) == ""){
                        $("#pmSpan").html("A little personal message...");
                    }else{
                      //$("#pmSpan").html(encodeURIComponent($(this).val()));  
                    }

                    // Ajax call
                    //alert($("#pmSpan").html());
                    
                    $.ajax({
                        type:"POST",
                        url:"/logged/ajax/savepm",
                        data: "pm=" + encodeURIComponent($.trim($(this).val())),
                        success: function(newPm){
                            $("#pmSpan").html(newPm);  
                        }
                    });
                    
                    $("#pmEdit").show();
                    $(this).remove();
                });
                
                // Deep Linking for the tabs------ 
                $.address.change(function() {

                    switch ($.address.hash()) {
                        
                    case "timelineView":
                        $('.UITab').removeClass('activeTab');
                        $('#timeline').removeClass("noteboard").addClass("timeline");
                        $("#timelineTab").addClass('activeTab');
                        $('#infoDisplay').hide();
                        $('#noteboard').hide();
                        $('#scapes').show();
                        break;
                    
                    case "infoView":
                        
                        $('.UITab').removeClass('activeTab');
                        $("#infoTab").addClass('activeTab');
                        $('#scapes').hide();
                        $('#noteboard').hide();
                        $('#infoDisplay').show();

                        if (!O.profileInfoLoaded) {
                            // Ajax call
                            $("#infoDisplay").html(O.progressBar);
                            $("#infoDisplay .progressBar").attr("style", "margin-top: 50px");
                            $.ajax({
                                type: "POST",
                                url: "/logged/ajax/infodisplay",
                                data: "ownerid=" + $("#owner").attr("userid") + "&ownerUsername=" + $("#owner").attr("username") + "&ownerFirstname=" + $("#owner").attr("firstname"),
                                success: function(data) {

                                    $("#infoDisplay").html(data);
                                    O.profileInfoLoaded = true;
                                }
                            });
                        }
                        break;
                    case "noteboardView":
                        $('.UITab').removeClass('activeTab');
                        $('#timeline').removeClass("timeline").addClass("noteboard");
                        $('#noteboardTab').addClass('activeTab');
                        $('#scapes').hide();
                        $('#infoDisplay').hide();
                        $('#noteboard').show();

                        if (!O.noteboardLoaded) {
                            // Ajax call
                            $("#noteboard").html(O.progressBar);
                            $("#noteboard .progressBar").attr("style", "margin-top: 50px");
                            $.ajax({
                                type: "POST",
                                url: "/logged/ajax/noteboard",
                                data: "ownerid=" + $("#owner").attr("userid"),
                                success: function(data) {

                                    $("#noteboard").html(data);
                                    O.noteboardLoaded = true;
                                }
                            });
                        }
                        break;
                    }

                });
                    
                // Set load more button
                    $("#loadMoreBtn").click(function(){
            
                        if(!window.mult){
                            window.mult = 0;
                        }
            
                        offset = 10*window.mult + 10;             
                        window.mult++;
                        
                        O.scape.load("profile",$("#owner").attr("userid"),offset);
                    });
                
                
            // Scape readmore button animation config-----
            $(".scape").live("mouseenter",function() {
                $(".readMore", this).stop(true, true).fadeIn("fast");
            });
            $(".scape").live("mouseleave",function() {
                $(".readMore", this).stop(true, true).fadeOut("fast");
            });
                
				
            }
        },
           // photoPage stuff
           photoPage: {

               bootloader: function() {
                   function toggleEditor(action) { 

                       if (action == "hide") {
                           $('#detailEditor').hide();
                           $('.photoActions').show();
                           $('#lowerControls').show();
                           $('.photoNaviControls').show();
                           $("#caption").show();
                           if ($("#caption").html() == "") {
                               $('#addCaption').show();
                           }
                           $(".metadata").show();
                           toggleShortcuts("add");
                       } else {
                           $('#detailEditor').show();

                           $('#addCaption').hide();
                           $("#caption").hide();
                           $(".metadata").hide();
                           $('.photoActions').hide();
                           $('#lowerControls').hide();
                           $('.photoNaviControls').hide();
                           toggleShortcuts("remove");

                           $("#editLocation").val($("#location").html());
                           $("#editCaption").val(O.stripTags($("#caption").html()));
                       }
                   }
               }
        },
        publisher :{
            // Creates a full or mini publisher based on the arguments provided
            // The "x" is just there to make this ugly thing collapsble, no one in their
            // right minds would want to look at this code till there is a cleaner way.
            // nav: O.publisher.create();
            create : function x(editorid,type){

                function resizeImages(){
                    $("#"+editorid+"_editor").contents().find("img").each(function() {

                        var align = $(this).attr("align");
                        switch(align){
                            case "center":
                                $(this).attr("class","CGImage center");
                                break;
                            case "left":
                                $(this).attr("class","CGImage left");
                                break;
                            case "right":
                                $(this).attr("class","CGImage right");
                                break;
                        }

                        var maxWidth = 400; // Max width for the image
                        var maxHeight = 300;    // Max height for the image
                        var ratio = 0;  // Used for aspect ratio
                        var width = $(this).width();    // Current image width
                        var height = $(this).height();  // Current image height

                        // Check if the current width is larger than the max
                        if(width > maxWidth){
                            ratio = maxWidth / width;   // get ratio for scaling image
                            $(this).css("width", maxWidth); // Set new width
                            $(this).css("height", height * ratio);  // Scale height based on ratio
                            height = height * ratio;    // Reset height to match scaled image
                            width = width * ratio;    // Reset width to match scaled image
                            $(this).attr("width",width).attr("height",height);
                        }

                        // Check if current height is larger than max
                        if(height > maxHeight){
                            ratio = maxHeight / height; // get ratio for scaling image
                            $(this).css("height", maxHeight);   // Set new height
                            $(this).css("width", width * ratio);    // Scale width based on ratio
                            width = width * ratio;    // Reset width to match scaled image
                            $(this).attr("width",width).attr("height",maxHeight);
                        }


                    });
                }

                function setEditorBodyCss(){
                    $("#"+editorid+"_editor").contents().find("head").append(
                        "<style>\n\
    a {color:#6666d5 !important;text-decoration:none !important;}\n\
    a:hover, a:focus {color:#3399ff !important;text-decoration:underline !important;cursor:pointer !important;outline: none !important;}\n\
    a:active {color: #000099 !important;text-decoration: underline !important;outline: none !important;}\n\
    .center {display:block; margin-left:auto; margin-right:auto;}\n\
    img{padding: 13px;}\n\
    </style>");
                }

                function IEFix(editor){
                    var content = $.trim(editor.getEditorHTML());
                    if (content == "<BR>" || content == "<P>&nbsp;</P>"){
                        editor.setEditorHTML("");
                    }
                }

                function placeHolderImage(){
                    $("#"+editorid+"_editor").contents().find("img").each(function(){
                        var img = $(this);

                        if(img.hasClass("yui-img") && img.attr("src") == "http://yui.yahooapis.com/2.8.1/build/editor/assets/skins/sam/blankimage.png"){
                            var domain = top.location.host;
                            img.attr("src","http://"+domain+"/graphics/en/logos/the_big_o_placeholder_103px.png");
                        }
                    });

                    
                }

                function configureWindows(){
                    $(".yui-toolbar-block").attr("title","Center");
                    $(".createlink_target").attr("checked",true);
                }
                
                switch(type){
                    case "cleaner":
                        window.cleaner = new YAHOO.widget.Editor(editorid, {});
                        break;
                    case "scapePublisher":
                        window.scapePublisher = new YAHOO.widget.Editor(editorid, {
                            //height: '200px',
                            // width: '468px',
                            // dompath: true, //Turns on the bar at the bottom
                            animate: true, //Animates the opening, closing and moving of Editor windows
                           // focusAtStart: true,
                            toolbar: {
                            //    collapse: true,
                                titlebar: ' ',
                                buttons: [

                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'textstyle',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Bold',
                                        value: 'bold'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Italic',
                                        value: 'italic'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Underline',
                                        value: 'underline'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Strike through',
                                        value: 'strikethrough'
                                    },
                                    {
                                        type: 'separator'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Super script',
                                        value: 'superscript',
                                        disabled: true
                                    },
                                    {
                                        type: 'push',
                                        label: 'Sub script',
                                        value: 'subscript',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'indentlist',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create an unordered list',
                                        value: 'insertunorderedlist'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Create an ordered list',
                                        value: 'insertorderedlist'

                                    },{
                                        type: 'push',
                                        label: 'Remove Formatting',
                                        value: 'removeformat',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'insertitem',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create HTML link',
                                        value: 'createlink',
                                        disabled: true
                                    },
                                    {
                                        type: 'push',
                                        label: 'Insert Image',
                                        value: 'insertimage'
                                    }
                                    ]
                                }
                                ]
                            }
                        });

                        scapePublisher.on('editorContentLoaded', function() {
                            setEditorBodyCss();
                        }, scapePublisher, true);
                        scapePublisher.on('editorKeyDown', function() {
                            IEFix(this);
                        }, scapePublisher, true);
                        scapePublisher.on('windowinsertimageClose', function() {
                            resizeImages();
                        }, scapePublisher, true);
                        scapePublisher.on('beforeOpenWindow', function() {
                            placeHolderImage();
                            configureWindows();
                        }, scapePublisher, true);
                       scapePublisher.on('editorMouseDown', function() {
                            $("div.heading").slideUp("fast");
                            $(".yui-editor-editable-container").attr("style","height:200px");                     
                        }, scapePublisher, true);
            

                    
                        scapePublisher.render();
                
                        break;

                    case "replyPublisher":
                        window.replyPublisher = new YAHOO.widget.Editor(editorid, {
                            //height: '200px',
                            // width: '468px',
                            // dompath: true, //Turns on the bar at the bottom
                            animate: true, //Animates the opening, closing and moving of Editor windows
                            focusAtStart: true,
                            toolbar: {
                           //     collapse: true,
                                titlebar: ' ',
                                buttons: [

                                {
                                    type: 'separator'
                                },

                                {
                                    group: 'textstyle',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Bold',
                                        value: 'bold'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Italic',
                                        value: 'italic'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Underline',
                                        value: 'underline'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Strike through',
                                        value: 'strikethrough'
                                    },
                                    {
                                        type: 'separator'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Super script',
                                        value: 'superscript',
                                        disabled: true
                                    },
                                    {
                                        type: 'push',
                                        label: 'Sub script',
                                        value: 'subscript',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'indentlist',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create an unordered list',
                                        value: 'insertunorderedlist'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Create an ordered list',
                                        value: 'insertorderedlist'

                                    },{
                                        type: 'push',
                                        label: 'Remove Formatting',
                                        value: 'removeformat',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'insertitem',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create HTML link',
                                        value: 'createlink',
                                        disabled: true
                                    }
                                    ]
                                }
                                ]
                            }
                        });

                        replyPublisher.on('editorContentLoaded', function() {
                            setEditorBodyCss();
                        }, replyPublisher, true);

                        replyPublisher.on('editorKeyDown', function() {
                            IEFix(this);
                        }, replyPublisher, true);
                        
                        replyPublisher.on('beforeOpenWindow', function() {
                            configureWindows();
                        }, replyPublisher, true);
                        
                        replyPublisher.on('editorMouseDown', function() {
                            $("div.heading").slideUp("fast");
                            $("#publisher .yui-editor-editable-container").attr("style","height:200px");                     
                        }, replyPublisher, true);
                        
                        replyPublisher.render();
                        break;
                    case "commentPublisher":
                        // stupid IE fix yet again, have to use "window." for some reason
                        window.commentPublisher = new YAHOO.widget.Editor(editorid, {
                            height: '100px',
                       //   width: '338px',
                            // dompath: true, //Turns on the bar at the bottom
                            animate: true, //Animates the opening, closing and moving of Editor windows
                            focusAtStart: true,
                            toolbar: {
                            //    collapse: false,
                                titlebar: ' ',
                                buttons: [

                                {
                                    type: 'separator'
                                },

                                {
                                    group: 'textstyle',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Bold',
                                        value: 'bold'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Italic',
                                        value: 'italic'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Underline',
                                        value: 'underline'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Strike through',
                                        value: 'strikethrough'
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'indentlist',
                                    label: '',
                                    buttons: [

                                    {
                                        type: 'push',
                                        label: 'Remove Formatting',
                                        value: 'removeformat',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'insertitem',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create HTML link',
                                        value: 'createlink',
                                        disabled: true
                                    }
                                    ]
                                }
                                ]
                            }
                        });

                        commentPublisher.on('editorContentLoaded', function() {
                            setEditorBodyCss();
                        }, commentPublisher, true);

                        commentPublisher.on('editorKeyDown', function() {
                            IEFix(this);
                        }, commentPublisher, true);

                        commentPublisher.on('beforeOpenWindow', function() {
                            configureWindows();
                        }, commentPublisher, true);
                        
                        commentPublisher.render();
                        break;
                        
                    case "scapeEditor":
                        
                        // "window." needs to be added for IE to work, wierdest errors, stupid IE.
                        window.scapeEditor = new YAHOO.widget.Editor(editorid, {
                            height: '300px',
                            // width: '468px',
                            // dompath: true, //Turns on the bar at the bottom
                            animate: true, //Animates the opening, closing and moving of Editor windows
                            focusAtStart: true,
                            toolbar: {
                            //    collapse: true,
                                titlebar: ' ',
                                buttons: [

                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'textstyle',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Bold',
                                        value: 'bold'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Italic',
                                        value: 'italic'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Underline',
                                        value: 'underline'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Strike through',
                                        value: 'strikethrough'
                                    },
                                    {
                                        type: 'separator'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Super script',
                                        value: 'superscript',
                                        disabled: true
                                    },
                                    {
                                        type: 'push',
                                        label: 'Sub script',
                                        value: 'subscript',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'indentlist',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create an unordered list',
                                        value: 'insertunorderedlist'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Create an ordered list',
                                        value: 'insertorderedlist'

                                    },{
                                        type: 'push',
                                        label: 'Remove Formatting',
                                        value: 'removeformat',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'insertitem',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create HTML link',
                                        value: 'createlink',
                                        disabled: true
                                    },
                                    {
                                        type: 'push',
                                        label: 'Insert Image',
                                        value: 'insertimage'
                                    }
                                    ]
                                }
                                ]
                            }
                        });

                        scapeEditor.on('editorContentLoaded', function() {
                            setEditorBodyCss();
                        }, scapeEditor, true);
                        scapeEditor.on('editorKeyDown', function() {
                            IEFix(this);
                        }, scapeEditor, true);
                        scapeEditor.on('windowinsertimageClose', function() {
                            resizeImages();
                        }, scapeEditor, true);
                        scapeEditor.on('beforeOpenWindow', function() {
                            placeHolderImage();
                            configureWindows();
                        }, scapeEditor, true);

                        scapeEditor.render();
                
                        break;

                    case "replyEditor":
                        window.replyEditor = new YAHOO.widget.Editor(editorid, {
                            height: '150px',
                            // dompath: true, //Turns on the bar at the bottom
                            animate: true, //Animates the opening, closing and moving of Editor windows
                            focusAtStart: true,
                            toolbar: {
                            //    collapse: false,
                                titlebar: ' ',
                                buttons: [

                                {
                                    type: 'separator'
                                },

                                {
                                    group: 'textstyle',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Bold',
                                        value: 'bold'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Italic',
                                        value: 'italic'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Underline',
                                        value: 'underline'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Strike through',
                                        value: 'strikethrough'
                                    },
                                    {
                                        type: 'separator'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Super script',
                                        value: 'superscript',
                                        disabled: true
                                    },
                                    {
                                        type: 'push',
                                        label: 'Sub script',
                                        value: 'subscript',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'indentlist',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create an unordered list',
                                        value: 'insertunorderedlist'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Create an ordered list',
                                        value: 'insertorderedlist'

                                    },{
                                        type: 'push',
                                        label: 'Remove Formatting',
                                        value: 'removeformat',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'insertitem',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create HTML link',
                                        value: 'createlink',
                                        disabled: true
                                    }
                                    ]
                                }
                                ]
                            }
                        });

                        replyEditor.on('editorContentLoaded', function() {
                            setEditorBodyCss();
                        }, replyEditor, true);

                        replyEditor.on('editorKeyDown', function() {
                            IEFix(this);
                        }, replyEditor, true);
                        
                        replyEditor.on('beforeOpenWindow', function() {
                            configureWindows();
                        }, replyEditor, true);

                        replyEditor.render();
                        break;

                    case "commentEditor":
                        // stupid IE fix yet again, have to use "window." for some reason
                        window.commentEditor = new YAHOO.widget.Editor(editorid, {
                            height: '100px',
                            width: '338px',
                            // dompath: true, //Turns on the bar at the bottom
                            animate: true, //Animates the opening, closing and moving of Editor windows
                            focusAtStart: true,
                            toolbar: {
                            //    collapse: false,
                                titlebar: ' ',
                                buttons: [

                                {
                                    type: 'separator'
                                },

                                {
                                    group: 'textstyle',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Bold',
                                        value: 'bold'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Italic',
                                        value: 'italic'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Underline',
                                        value: 'underline'
                                    },
                                    {
                                        type: 'push',
                                        label: 'Strike through',
                                        value: 'strikethrough'
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'indentlist',
                                    label: '',
                                    buttons: [

                                    {
                                        type: 'push',
                                        label: 'Remove Formatting',
                                        value: 'removeformat',
                                        disabled: true
                                    }
                                    ]
                                },
                                {
                                    type: 'separator'
                                },
                                {
                                    group: 'insertitem',
                                    label: '',
                                    buttons: [
                                    {
                                        type: 'push',
                                        label: 'Create HTML link',
                                        value: 'createlink',
                                        disabled: true
                                    }
                                    ]
                                }
                                ]
                            }
                        });

                        commentEditor.on('editorContentLoaded', function() {
                            setEditorBodyCss();
                        }, commentEditor, true);

                        commentEditor.on('editorKeyDown', function() {
                            IEFix(this);
                        }, commentEditor, true);

                        commentEditor.on('beforeOpenWindow', function() {
                            configureWindows();
                        }, commentEditor, true);

                        commentEditor.render();
                        break;
                }

            },
                
            // nav: O.publisher.remove();
            remove: function(panelid,editorid){
                if(O.pubfull){
                    O.pubfull.removeInstance(editorid);
                }
                $("#"+panelid).html("");
            }
        },
        
        animate : {
            
            photo:{
            // Displays or hides the detail editor for photos
            // nav: O.animate.photo.toggleEditor();
                toggleEditor: function(action){

                    if (action == "hide"){
                        $('#detailEditor').hide();
                        $('.photoActions').show();
                        $('#lowerControls').show();
                        $('.photoNaviControls').show();
                        $("#caption").show();
                        if($("#caption").html() == ""){
                            $('#addCaption').show();
                        }
                        $(".metadata").show();

                    } else if (action == "show") {
                        $('#detailEditor').show();
            
                        $('#addCaption').hide();
                        $("#caption").hide();
                        $(".metadata").hide();
                        $('.photoActions').hide();
                        $('#lowerControls').hide();
                        $('.photoNaviControls').hide();

            
                        $("#editLocation").val(O.htmlspecialcharsDecode($("#location").html()));
                        $("#editCaption").val(O.htmlspecialcharsDecode($("#caption").html().replace(/<br>|<br \/>/gi,"\r")));
                    }                    
                }
            },
            
            jelloon: function(text,affirm){
                
                if(!affirm){                 
                    $("#jelloonNeg").remove();
                    $("#mainWrapper").prepend(O.jellooNeg);
                    $("#jelloonNeg").html(text);                
                }else{
                    $("#jelloonAffirm").remove();
                    $("#mainWrapper").prepend(O.jelloonAffirm);
                    $("#jelloonAffirm").html(text);
                }
                
            },
            
            // Displays the newly loaded scape in the timeline
            // nav: O.animate.displayNewLoadedScapes();

            displayNewLoadedScapes: function(scapeData){
                $(".progressBar").remove();
                $("#timelineFeed").append(scapeData);
                $("#loadMoreBtn").show();

            },

            // Displays scape in the timeline based on the scape data provided
            // nav: O.animate.displayPostedScape();

            displayPostedScape : function (scapeData){
                $("#timelineFeed").prepend(scapeData);
                $("#timelineFeed div.scape").slideDown("slow");
                $("#noPosts").remove();
            },

            // Displays reply under the scape based on the reply data provided
            // nav: O.animate.displayPostedReply();
            displayPostedReply: function (replyData){
                $("#batch1").append(replyData);
                $("#replies div.CGReply").slideDown("slow");
            },
            // Displays scape error under the home page publisher
            // nav: O.animate.displayScapeError();
            displayScapeError : function (){
                $("#scapeError").fadeIn(2000,function(){
                    $("#scapeError").fadeOut(2000);
                });
                O.animate.unlockPub(true);
            },

            // Displays reply error under the reply publisher
            // nav: O.animate.displayReplyError();
            displayReplyError : function(){
                $("#replyError").fadeIn(2000,function(){
                    $("#replyError").fadeOut(2000);
                });
                O.animate.unlockPub(true,"reply");
            },
            
            // Displays reply confirmation under the reply publisher
            // nav: O.animate.displayReplyConfirmation();
            displayReplyConfirmation: function(){
                $("#replyConfirmation").fadeIn(2000,function(){
                    $("#replyConfirmation").fadeOut(2000);
                });
            },

            // Displays reply confirmation under the reply publisher
            // nav: O.animate.displayCommentWindow();
            displayReplyWindow: function(type){
                O.windowManager.drawWindow("replyWindow", "Showing all comments on this reply", "center", "570", "478");
                O.windowManager.setWindowContents("replyWindow",O.progressBar);
                $("#replyWindow .progressBar").attr("style","margin-top: 180px;");
                $("#replyWindow").addClass("allComments");
            },

            displayListUsersWindow: function(type){


                switch(type){
                    case "like":
                        O.windowManager.drawWindow("listUsersWindow", "These people like this", "center", "490", "478");
                        
                        break;
                    case "dislike":
                        O.windowManager.drawWindow("listUsersWindow", "These people don't like this", "center", "490", "478");

                        break;
                    case "watch":
                        O.windowManager.drawWindow("listUsersWindow", "These people are now watching you", "center", "490", "478");

                        break;
                    case "reply":
                        O.windowManager.drawWindow("listUsersWindow", "These people replied to your scape", "center", "490", "478");

                        break;
                    case "comment":
                        O.windowManager.drawWindow("listUsersWindow", "These people commented on this", "center", "490", "478");
                        break;
                    
                    case "note":
                        O.windowManager.drawWindow("listUsersWindow", "These people left you notes", "center", "490", "478");    
                        break;
                }

                O.windowManager.setWindowContents("listUsersWindow",O.progressBar);
                $("#listUsersWindow").addClass("UITableView").css("width","502px");
                $("#listUsersWindow .progressBar").attr("style","margin-top: 180px;");
                
            },
        
            // Displays the comment box under the element.
            // nav: O.animate.displayCommentBox();
            displayCommentBox: function(elmid){

                var idType,id,idAndType
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;

                
                if($("#"+elmid+" div.commentComponent").is(':visible')){

                    window.commentPublisher.destroy();
                    window.commentPublisherDestroyed = true;
                    $("#"+elmid+" div.commentComponent").fadeOut();

                }else{
                    
                    $("div.commentComponent").hide();

                    if (!window.commentPublisherDestroyed && window.commentPublisherDestroyed !== undefined){
                        window.commentPublisher.destroy();
                        window.commentPublisherDestroyed = true;
                    }

                    O.publisher.create("minieditor"+id,"commentPublisher");
                    window.commentPublisherDestroyed = false;
                    
                    $("#"+elmid+" div.commentComponent").fadeIn(400,function(){});
                }
            },
            // Displays and or updates comment count under element
            // nav: O.animate.commentCount();
            commentCount: function(elmid){
                var showAllCommentsBtnMarkup,noOfComments,idType,id,idAndType;

                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;
                
                showAllCommentsBtnMarkup = $("#"+elmid+" .commentCount").html();
                $("#"+elmid+" .showAllCommentsBtnLink").hide().fadeIn().css("display","block").html("<span class=\"UIIcon px16 comment\"></span>Comment posted!");

                
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

                setTimeout(function() {
                    $("#"+elmid+" .showAllCommentsBtnLink").hide().fadeIn(400,function(){
                        O.animate.unlockMiniPub(false,elmid);
                    })
                    .html(showAllCommentsBtnMarkup).css("display","block");
                }, 3000);
            },
            
            // Locks the publisher
            // nav: O.animate.lockPub();
            
            lockPub : function(){
                $("#postBtnLink").addClass("unavailable");
                $("#postBtnLink")[0].onclick=null;
                
               $("#pubSpinner").show();
            },
            // Unlocks the publisher
            // nav: O.animate.unlockPub();
            unlockPub : function (preserve,type){

                if(!preserve){
                    if(type === "scape"){
                        scapePublisher.setEditorHTML("");
                    }else if(type === "reply"){
                        replyPublisher.setEditorHTML("");
                    }
                    $("#headingBox").val("");
                    // Reset the counter
                    $("span.charCounter").html("100").css("color", "#00ad00");
                    $("#privacyList").html("");
                }

                $("#postBtnLink").removeClass("unavailable");
                $("div.headingPanel").slideUp("fast");

                $('#postBtnLink')[0].onclick = function(){
                    // Bind the scape/reply creation method back to the button
                    if(type === "scape"){
                        O.scape.create();
                    }else if(type === "reply"){
                        O.reply.create();
                    }
                };
                
                $("#pubSpinner").hide();
            },
            // locks the given elements comment mini publisher
            // nav: O.animate.lockMiniPub();
            lockMiniPub: function(elmid){
                $("#"+elmid+" .UIButton.post").addClass("unavailable");
                $("#"+elmid+" .UIButton.post")[0].onclick=null;
            },

            // locks the given elements comment mini publisher
            // nav: O.animate.unlockMiniPub();
            unlockMiniPub: function(preserve,elmid){
                if(!preserve){
                    commentPublisher.setEditorHTML("");
                }
                $("#"+elmid+" .UIButton.post").removeClass("unavailable");
                $("#"+elmid+" .UIButton.post")[0].onclick = function(){
                    O.comment.create(elmid);
                };
            },

            // locks the UI with a pageOverlay
            // nav: O.animate.lockUI();
            lockUI : function(){
                $("#pageOverlay").show();
            },

            // unlock the UI
            // nav: O.animate.unlockUI();
            unlockUI : function(){
                $("#pageOverlay").hide();
            },

            // unlock the element with the given selector
            // nav: O.animate.unlockElm();
            unlockElm : function(selector){
                $(selector).css({
                    position:"relative",
                    zIndex:"3"
                });
            },

            // Animation for likeing an element
            // nav: O.animate.like();
            like: function(elmid,html){
                
                var idType,id,idAndType
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;

                $("#"+elmid+" .likesAndDislikes").html(html);
                $("#"+elmid+" .userOpinions").hide();
                $("#"+elmid+" .userOpinions").fadeIn("fast");

                var content = $("#"+elmid+" .likeBtn").html();

                if (content == "<a>Like</a>" || content == "<A>Like</A>"){
                    $("#"+elmid+" .likeBtn").html("<a>Remove like</a>");

                }
                if (content == "<a>Remove like</a>" || content == "<A>Remove like</A>"){
                    $("#"+elmid+" .likeBtn").html("<a>Like</a>");

                }
                var contentOfDislike = $("#"+elmid+" .dislikeBtn").html();
                if (contentOfDislike == "<a>Remove dislike</a>" || contentOfDislike == "<A>Remove dislike</A>")
                {
                    $("#"+elmid+" .dislikeBtn").html("<a>Dislike</a>");
                }
            },

            // Animation for dislikeing an element
            // nav: O.animate.dislike();
            dislike: function(elmid,html){

                var idType,id,idAndType
                idAndType = O.getIdAndType(elmid);
                id = idAndType.id;
                idType = idAndType.idType;

                $("#"+elmid+" .likesAndDislikes").html(html);
                $("#"+elmid+" .userOpinions").hide();
                $("#"+elmid+" .userOpinions").fadeIn("fast");

                var content = $("#"+elmid+" .dislikeBtn").html();
                if (content == "<a>Dislike</a>" || content == "<A>Dislike</A>"){
                    $("#"+elmid+" .dislikeBtn").html("<a>Remove dislike</a>");

                }
                if (content == "<a>Remove dislike</a>" || content == "<A>Remove dislike</A>"){
                    $("#"+elmid+" .dislikeBtn").html("<a>Dislike</a>");

                }
                var contentOfLike = $("#"+elmid+" .likeBtn").html();
                if (contentOfLike == "<a>Remove like</a>" || contentOfLike == "<A>Remove like</A>")
                {
                    $("#"+elmid+" .likeBtn").html("<a>Like</a>");
                }
            },
              
            // Animation configurations for editing scapes.
            // nav: O.animate.editScape();
            editScape: function(content,title,type){

                if(type == "edit"){

                    O.animate.lockUI();
                    O.animate.unlockElm(".CGPost");

                    $("#headingBox").html($("#postTitle").html());
                    scapeEditor.setEditorHTML(scapeEditor.cleanHTML(content));
                    $("#scapeEditorHolder").show();

                    $("#headingEditor").show();
                    $("#scapeDoneEditingBtn").show();

                    $("#scapeContent").hide();
                    $("#postTitle").hide();
                    $("#scapeOptions").hide();

                    $('#editScapeToolTip').fadeIn(400,function(){
                        setTimeout('$(\'.editInfo\').fadeOut(400)', 3000);
                    });

                }else if (type == "done_nochange"){

                    if (title == ""){
                        $("#postTitle").hide();
                    }else{
                        $("#postTitle").show();
                    }
                    O.animate.unlockUI();
                    $(".CGPost").removeAttr("style");
                    $("#scapeContent").show();
                    $("#scapeContent").html(content);
                    scapeEditor.setEditorHTML("");
                    $("#scapeEditorHolder").hide();

                    $("#headingEditor").hide();

                }else if (type == "done_change"){
                    if (title == ""){
                        $("#postTitle").html("");
                        $("#postTitle").hide();
                    }else{
                        $("#postTitle").html(title);
                        $("#postTitle").show();
                    }
                    $("#scapeContent").show();
                    $("#scapeOptions").show();

                    $("#scapeDoneEditingBtn").hide();
                    $(".CGPost").removeAttr("style");
                    $("#scapeContent").html(content);
                    scapeEditor.setEditorHTML("");
                    $("#scapeEditorHolder").hide();

                    $("#headingEditor").hide();
                    $("#scapeOptionsMenu").hide();
                    $('#doneEditingScapeToolTip').fadeIn(400,function(){
                        setTimeout('$(\'.editInfo\').fadeOut(400)', 3000);
                    });
                    O.animate.unlockUI();
                }

            }
        },
        // example: onclick='O.windowManager.drawWindow("akayWindow", "Testing", "center", "582", "478");O.windowManager.setWindowContents("akayWindow",O.progressBar)'
        windowManager: {

            // Jquery plugin for window positioning.
            // nav: O.windowManager.initPositioner();
            initPositioner: function(){

                (function($){
                    $.fn.positionCenter = function(options) {
                        var pos = {
                            sTop : function() {
                                return window.pageYOffset || document.documentElement && document.documentElement.scrollTop ||	document.body.scrollTop;
                            },
                            wHeight : function() {
                                return window.innerHeight || document.documentElement && document.documentElement.clientHeight || document.body.clientHeight;
                            }
                        };
                        return this.each(function(index) {
                            if (index == 0) {
                                var $this = $(this);
                                var elHeight = $this.outerHeight();
                                var elTop = pos.sTop() + (pos.wHeight() / 2) - (elHeight / 2);
                                $this.css({
                                    top: elTop,
                                    left: (($(window).width() - $this.outerWidth()) / 2) + 'px'
                                });
                            }
                        });
                    };
                })(jQuery);

            },
            //--------------------------------------
            // function takes 5 possible parameters.
            //-----------------------------------------
            // An id (any arbitrary value which will be placed as an id attribute on the element)
            //-----------------------------------------
            // A title (will be displayed in the title bar)
            //-----------------------------------------
            // A position, the only keyword I have so far is 'center', which centers a
            // window independent of the page content (dependent on the viewport size)
            //-----------------------------------------
            // A width and a height (they will be measured using pixels. NOTE: DO NOT ENTER 'px' WITH THE VALUE)

            // nav: O.windowManager.drawWindow();
            drawWindow: function(id, title, position, width, height){

                // Initialize the plugin
                O.windowManager.initPositioner();
                // Break everything into separate components
                var UIWindowTitleBar = '<div class="titleBar"><h2>'+title+'</h2><span class="closeBtn" title="Close" onclick="$(\'.UIWindow\').fadeOut(function(){$(\'#windowCompositionLayer\').remove();});O.animate.unlockUI();"><a><span class="UIIcon px16 cross"></span></a></span></div>';
                var UIWindowView = '<div class="UIWindowContents"></div>';
                var UIWindow = '<div class="UIWindow" id='+id+'>' + UIWindowTitleBar + UIWindowView + '</div>';

                // Attach the Window Composition Layer
                $('#mainWrapper').prepend('<div id="windowCompositionLayer"></div>');

                // Draw the window and insert parameters
                $(UIWindow).appendTo('#windowCompositionLayer');
                $('#' + id + ' .UIWindowContents').attr('style', 'width:' + width + 'px;height:' + height + 'px;');
                $('#' + id + ' .UIWindow').show().positionCenter();

                // This code handles the 'center' keyword
                if (position == 'center') {
                    $('#' + id + '.UIWindow').positionCenter();
                }

                O.animate.lockUI();
            },
            
            // This function adds content to a window whose id you pass in as
            // a parameter. It takes HTML markup as a second parameter
            // nav: O.windowManager.setWindowContents();
            setWindowContents: function(id,markup){
                
                $('#' + id + ' .UIWindowContents').html(markup);

            }
        },

        // GENERAL METHODS -------------------------
        
        htmlspecialchars: function(text){
            return text.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
        },
        
        htmlspecialcharsDecode: function (text){
           return text.replace(/&amp;/g,"&").replace(/&lt;/g,"<").replace(/&gt;/g,">"); 
        },
        
        //nav: O.isNumeric();
        isNumeric: function (mixed_var) {
           // Returns true if value is a number or a numeric string
           return (typeof(mixed_var) === 'number' || typeof(mixed_var) === 'string') && mixed_var !== '' && !isNaN(mixed_var);
        },

        /**
             * Process text/HTML input and returns true if it has visaible content
             * according to the arument passed in allowImg, then it counts images as visible content
             *
             * @param str {string} : string to be processed.
             * @param allowImg {boolean}: (true|false)
             * @return true | false
             * @type boolean
             * @nav: O.hasText();
             */

        hasText : function(str,allowImg){
            if (allowImg){
                if (str.replace(/(<[^img].*?>)|(&nbsp;)/ig, '').replace(/\s/g,"") != ""){
                    return true;
                }
                else{
                    return false;
                }
            }else{
                if (str.replace(/(<.*?>)|(&nbsp;)/ig, '').replace(/\s/g,"") != ""){
                    return true;
                }
                else{
                    return false;
                }
            }
            
        },

        /**
             * Process text/HTML input based on type of input.
             *
             * @param str {string} : string to be processed.
             * @param type {string}: (scape|reply|comment|text)
             * @return Processed string
             * @type string
             * @nav: O.processInput();
             */

        processInput : function(str,type){
            
            switch(type){
                case "scape":
                    
                    str = sanatize(str);
                    str = O.stripTags(str, "<br><s><strong><em><u><sub><sup><ol><ul><li><a><img>");

                    return str;
                    
                    break;
                case "reply":
                    
                    str = sanatize(str);
                    str = O.stripTags(str, "<br><s><strong><em><u><sub><sup><ol><ul><li><a>");
                    return str;

                    break;
                case "comment":

                    str = sanatize(str);
                    str = O.stripTags(str, "<br><s><strong><em><u><a>");
                    return str;

                    break;
                case "text":
                    
                    str = O.stripTags(str);
                    return str;
                    
                    break;
            }

            function sanatize(str){

                str = str.replace(/<\/div>/gi,"<br />");
                str = str.replace(/<\/p>/gi,"<br />");
                str = cleaner.cleanHTML(str);    
                
                str = O.stripAttr(str);
                                    
                str = O.stripTags(str, "<br><s><span><strong><strike><em><u><sub><sup><ol><ul><li><a><img><h1><h2><h3><h4><h5><h6><h7>");
                                                    
                $("#mainWrapper").prepend("<div id='temp' style='position: absolute; left: -240px;'>"+str+"</div>");

                $("#temp :header").each(function(){
                    var html = $(this).html();
                    $(this).replaceWith("<strong>"+html+"</strong><br /><br />");
                });
                                             
                $("#temp strike").each(function(){
                    var html = $(this).html();
                    $(this).replaceWith("<s>"+html+"</s>");
                });

                $("#temp span").each(function(){
                    var html = $(this).html();
                    var style = $(this).attr("style");
                    if (style){
                        if(style.match(/text-decoration: line-through;?/i)){
                            $(this).replaceWith("<s>"+html+"</s>");
                        }
                    }
                });

                   
                $("#temp span").each(function(){
                    var html = $(this).html();
                    var style = $(this).attr("style");

                    if (style){
                        if(style.match(/text-decoration: underline;?/i)){
                            $(this).replaceWith("<u>"+html+"</u>");
                        }
                    }
                });

                $("#temp span").each(function(){
                    var html = $(this).html();
                    var style = $(this).attr("style");

                    if (style){
                        if(style.match(/text-decoration: underline line-through;?/i) ||
                            style.match(/text-decoration: line-through underline;?/i)){

                            $(this).replaceWith("<s><u>"+html+"</u></s>");
                        }
                    }
                });

                $("#temp img").each(function(){

                    var align = $(this).attr("align");
                    var elm = this.attributes;

                    $(this).removeAttr("style");

                    switch(align){
                        case "center":
                            $(this).attr("class","CGImage center");
                            break;
                        case "left":
                            $(this).attr("class","CGImage left");
                            break;
                        case "right":
                            $(this).attr("class","CGImage right");
                            break;
                        default:
                            $(this).attr("class","CGImage center");
                            break;
                    }

                    var maxWidth = 400; // Max width for the image
                    var maxHeight = 300;    // Max height for the image
                    var ratio = 0;  // Used for aspect ratio
                    var width = $(this).width();    // Current image width
                    var height = $(this).height();  // Current image height

                    // Check if the current width is larger than the max
                    if(width > maxWidth){
                        ratio = maxWidth / width;   // get ratio for scaling image
                        //     $(this).css("width", maxWidth); // Set new width
                        //     $(this).css("height", height * ratio);  // Scale height based on ratio
                        height = height * ratio;    // Reset height to match scaled image
                        width = width * ratio;    // Reset width to match scaled image
                        $(this).attr("width",width).attr("height",height);
                    }

                    // Check if current height is larger than max
                    if(height > maxHeight){
                        ratio = maxHeight / height; // get ratio for scaling image
                        //    $(this).css("height", maxHeight);   // Set new height
                        //    $(this).css("width", width * ratio);    // Scale width based on ratio
                        width = width * ratio;    // Reset width to match scaled image
                        $(this).attr("width",width).attr("height",maxHeight);
                    }

                });
                
                $("#temp a").attr("target","_blank");

                $("#temp *").each(function(){

                    var tag = this.nodeName.toLowerCase();
                    var html = $(this).html();
                    
                    if (!tag.match(/br|img|hr|input|a/i)){
                        $(this).replaceWith("<"+tag+">"+html+"</"+tag+">");
                    }

                });

                str = $("#temp").html();
                $("#temp").remove();

                str = O.stripAttr(str);
                str = str.replace(/<(\/)*(\\?xml:|meta|link|span|font|del|ins|st1:|[ovwxp]:)((.|\s)*?)>/gi, ''); // Unwanted tags
                str = str.replace(/<br>/ig,"<br />");           // <br> to <br />
                str = str.replace(/<style(.*?)style>/gi, '');   // Style tags
                str = str.replace(/<script(.*?)script>/gi, ''); // Script tags
                str = str.replace(/<!--(.*?)-->/gi, '');        // HTML comments
                

                return str;
                
            }
        },

        /**
        * Strips HTML and PHP tags from a string along with their attributes
        *
        * @param str {string} : string to be processed.
        * @param allowed_tags {string}: allowed tags in the format "&lt;b&gt;&lt;br&gt;&lt;p&gt;&lt;h1&gt;"
        * @return Processed string
        * @type string
        * @nav: O.stripTags();
        */
       
        stripTags : function(str, allowed_tags){

            var key = '', allowed = false;
            var matches = [];
            var allowed_array = [];
            var allowed_tag = '';
            var i = 0;
            var k = '';
            var html = '';

            var replacer = function (search, replace, str) {
                return str.split(search).join(replace);
            };

            // Build allowed tags associative array
            if (allowed_tags) {
                allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
            }

            str += '';

            // Match tags
            matches = str.match(/(<\/?[\S][^>]*>)/gi);

            // Go through all HTML tags
            for (key in matches) {
                if (isNaN(key)) {
                    // IE7 Hack
                    continue;
                }

                // Save HTML tag
                html = matches[key].toString();

                // Is tag not in allowed list? Remove from str!
                allowed = false;

                // Go through all allowed tags
                for (k in allowed_array) {
                    // Init
                    allowed_tag = allowed_array[k];
                    i = -1;

                    if (i != 0) {
                        i = html.toLowerCase().indexOf('<'+allowed_tag+'>');
                    }
                    if (i != 0) {
                        i = html.toLowerCase().indexOf('<'+allowed_tag+' ');
                    }
                    if (i != 0) {
                        i = html.toLowerCase().indexOf('</'+allowed_tag)   ;
                    }

                    // Determine
                    if (i == 0) {
                        allowed = true;
                        break;
                    }
                }

                if (!allowed) {
                    str = replacer(html, "", str); // Custom replace. No regexing
                }
            }

            return str;

        },

        /**
         * Strips HTML of their attributes as configured
         *
         * @param str {string} : string to be processed.
         * @return Processed string
         * @type string
         * @nav: O.stripAttr();
         */
       
        stripAttr : function (str){

            return str.replace(/<[^>]*>/g, function(match)
            {

                if(match.match(/<img[^>]*>/i)){ // if image allow class and title

                    match = match.replace(/ ([^=]+)="[^"]*"/ig, function(match2, attributeName)
                    {
                        if (attributeName.match(/class|src|title|width|height|align/))
                        {
                            return match2;
                        }

                        return "";
                    });
                } else if(match.match(/<span[^>]*>/i)){ // if span allow style

                    match = match.replace(/ ([^=]+)="[^"]*"/ig, function(match2, attributeName)
                    {
                        if (attributeName.match(/style/))
                        {
                            return match2;
                        }

                        return "";
                    });
                } else if(match.match(/<a[^>]*>/i)){ // if span allow style

                    match = match.replace(/ ([^=]+)="[^"]*"/ig, function(match2, attributeName)
                    {
                        if (attributeName.match(/href|title|src|target/))
                        {
                            return match2;
                        }

                        return "";
                    });
                }
                else {
                    match = match.replace(/ ([^=]+)="[^"]*"/ig, function(match2, attributeName)
                    {
                        //                        if (attributeName.match(/href|title|src/))
                        //                        { // If you wish to allow an attribute through the filter, add it to the list with an || operator before it :) << Config area
                        //                            return match2;
                        //                        }

                        return "";
                    });
                }
                return match;
            }
            );
                
        },

        /**
             * Calculate the sha1 hash of a string (Do not touch).
             *
             * @param str {string} : string to be processed.
             * @return Processed sha1 hash
             * @type string
             * @nav: O.sha1();
             */
       
        sha1 : function (str) {

            var rotate_left = function (n,s) {
                var t4 = ( n<<s ) | (n>>>(32-s));
                return t4;
            };

            var cvt_hex = function (val) {
                var str="";
                var i;
                var v;

                for (i=7; i>=0; i--) {
                    v = (val>>>(i*4))&0x0f;
                    str += v.toString(16);
                }
                return str;
            };

            var blockstart;
            var i, j;
            var W = new Array(80);
            var H0 = 0x67452301;
            var H1 = 0xEFCDAB89;
            var H2 = 0x98BADCFE;
            var H3 = 0x10325476;
            var H4 = 0xC3D2E1F0;
            var A, B, C, D, E;
            var temp;

            str = this.utf8_encode(str);
            var str_len = str.length;

            var word_array = [];
            for (i=0; i<str_len-3; i+=4) {
                j = str.charCodeAt(i)<<24 | str.charCodeAt(i+1)<<16 |
                str.charCodeAt(i+2)<<8 | str.charCodeAt(i+3);
                word_array.push( j );
            }

            switch (str_len % 4) {
                case 0:
                    i = 0x080000000;
                    break;
                case 1:
                    i = str.charCodeAt(str_len-1)<<24 | 0x0800000;
                    break;
                case 2:
                    i = str.charCodeAt(str_len-2)<<24 | str.charCodeAt(str_len-1)<<16 | 0x08000;
                    break;
                case 3:
                    i = str.charCodeAt(str_len-3)<<24 | str.charCodeAt(str_len-2)<<16 | str.charCodeAt(str_len-1)<<8    | 0x80;
                    break;
            }

            word_array.push( i );

            while ((word_array.length % 16) != 14 ) {
                word_array.push( 0 );
            }

            word_array.push( str_len>>>29 );
            word_array.push( (str_len<<3)&0x0ffffffff );

            for ( blockstart=0; blockstart<word_array.length; blockstart+=16 ) {
                for (i=0; i<16; i++) {
                    W[i] = word_array[blockstart+i];
                }
                for (i=16; i<=79; i++) {
                    W[i] = rotate_left(W[i-3] ^ W[i-8] ^ W[i-14] ^ W[i-16], 1);
                }


                A = H0;
                B = H1;
                C = H2;
                D = H3;
                E = H4;

                for (i= 0; i<=19; i++) {
                    temp = (rotate_left(A,5) + ((B&C) | (~B&D)) + E + W[i] + 0x5A827999) & 0x0ffffffff;
                    E = D;
                    D = C;
                    C = rotate_left(B,30);
                    B = A;
                    A = temp;
                }

                for (i=20; i<=39; i++) {
                    temp = (rotate_left(A,5) + (B ^ C ^ D) + E + W[i] + 0x6ED9EBA1) & 0x0ffffffff;
                    E = D;
                    D = C;
                    C = rotate_left(B,30);
                    B = A;
                    A = temp;
                }

                for (i=40; i<=59; i++) {
                    temp = (rotate_left(A,5) + ((B&C) | (B&D) | (C&D)) + E + W[i] + 0x8F1BBCDC) & 0x0ffffffff;
                    E = D;
                    D = C;
                    C = rotate_left(B,30);
                    B = A;
                    A = temp;
                }

                for (i=60; i<=79; i++) {
                    temp = (rotate_left(A,5) + (B ^ C ^ D) + E + W[i] + 0xCA62C1D6) & 0x0ffffffff;
                    E = D;
                    D = C;
                    C = rotate_left(B,30);
                    B = A;
                    A = temp;
                }

                H0 = (H0 + A) & 0x0ffffffff;
                H1 = (H1 + B) & 0x0ffffffff;
                H2 = (H2 + C) & 0x0ffffffff;
                H3 = (H3 + D) & 0x0ffffffff;
                H4 = (H4 + E) & 0x0ffffffff;
            }

            temp = cvt_hex(H0) + cvt_hex(H1) + cvt_hex(H2) + cvt_hex(H3) + cvt_hex(H4);
            return temp.toLowerCase();
        },

        // nav: O.prefixConstruct();
        prefixConstruct: function(idType){
            switch(true){
                case(idType == "scape"):
                    return  "S";
                    break;
                case(idType == "reply"):
                    return  "R";
                    break;
                case(idType == "photo"):
                    return  "P";
                    break;
                case(idType == "comment"):
                    return  "C";
                    break;
            }
        },

        // takes in an elements actual id and returns its type and id number
        // nav: O.getIdAndType();
        getIdAndType: function(elmid){
            var idType;

            switch (true) {
            case(/S\d/).test(elmid):
                idType = "scape";
                break;
            case (/R\d/).test(elmid):
                idType = "reply";
                break;
            case (/C\d/).test(elmid):
                idType = "comment";
                break;
            case (/L\d/).test(elmid):
                idType = "like";
                break;
            case (/D\d/).test(elmid):
                idType = "dislike";
                break;
            case (/N\d/).test(elmid):
                idType = "notif";
                break;
            case (/W\d/).test(elmid):
                idType = "watch";
                break;
            case (/P\d/).test(elmid):
                idType = "photo";
                break;
            case (/(M)\d/).test(elmid):
                idType = "note";
                break;
            }

            var id =  elmid.replace(/S|R|C|L|D|N|W|P|M/,"");
            return {
                id: id,
                idType: idType
            };
        },
        
        // An ugly looking function that converts emoticon spans to text, takes the content of the element id given
        // its even more ugly because the ($("#"+idPrefix+id+" .emoticon.tongue").length > 0)?... part is a work around
        // to stop the function for failing when an emo was not matched.
        // nav: O.emoToText();
        emoToText : function(id,idType){
            
            var idPrefix = O.prefixConstruct(idType);

            ($("#"+idPrefix+id+" .emoticon.tongue").length > 0)?$("#"+idPrefix+id+" .emoticon.tongue").replaceWith(":P"):"";
            ($("#"+idPrefix+id+" .emoticon.laugh").length > 0)?$("#"+idPrefix+id+" .emoticon.laugh").replaceWith(":D"):"";
            ($("#"+idPrefix+id+" .emoticon.smile").length > 0)?$("#"+idPrefix+id+" .emoticon.smile").replaceWith(":)"):"";
            ($("#"+idPrefix+id+" .emoticon.sad").length > 0)?$("#"+idPrefix+id+" .emoticon.sad").replaceWith(":("):"";
            ($("#"+idPrefix+id+" .emoticon.cry").length > 0)?$("#"+idPrefix+id+" .emoticon.cry").replaceWith(":'("):"";
            ($("#"+idPrefix+id+" .emoticon.heart").length > 0)?$("#"+idPrefix+id+" .emoticon.heart").replaceWith("&lt;3"):"";
            ($("#"+idPrefix+id+" .emoticon.surprised").length > 0)?$("#"+idPrefix+id+" .emoticon.surprised").replaceWith(":O"):"";
            ($("#"+idPrefix+id+" .emoticon.angel").length > 0)?$("#"+idPrefix+id+" .emoticon.angel").replaceWith("(a)"):"";
            ($("#"+idPrefix+id+" .emoticon.blush").length > 0)?$("#"+idPrefix+id+" .emoticon.blush").replaceWith(":$"):"";
            ($("#"+idPrefix+id+" .emoticon.cool").length > 0)?$("#"+idPrefix+id+" .emoticon.cool").replaceWith("(cool)"):"";
            ($("#"+idPrefix+id+" .emoticon.wink").length > 0)?$("#"+idPrefix+id+" .emoticon.wink").replaceWith(";)"):"";
            ($("#"+idPrefix+id+" .emoticon.worried").length > 0)?$("#"+idPrefix+id+" .emoticon.worried").replaceWith(":S"):"";
            ($("#"+idPrefix+id+" .emoticon.speechless").length > 0)?$("#"+idPrefix+id+" .emoticon.speechless").replaceWith(":|"):"";
            ($("#"+idPrefix+id+" .emoticon.hardLaugh").length > 0)?$("#"+idPrefix+id+" .emoticon.hardLaugh").replaceWith("(xD)"):"";
            ($("#"+idPrefix+id+" .emoticon.hardLaughTongue").length > 0)?$("#"+idPrefix+id+" .emoticon.hardLaughTongue").replaceWith("(xP)"):"";
            ($("#"+idPrefix+id+" .emoticon.winkLaugh").length > 0)?$("#"+idPrefix+id+" .emoticon.winkLaugh").replaceWith(";D"):"";
            ($("#"+idPrefix+id+" .emoticon.winkTongue").length > 0)?$("#"+idPrefix+id+" .emoticon.winkTongue").replaceWith(";P"):"";
            ($("#"+idPrefix+id+" .emoticon.headBang").length > 0)?$("#"+idPrefix+id+" .emoticon.headBang").replaceWith("(banghead)"):"";
            ($("#"+idPrefix+id+" .emoticon.nerdy").length > 0)?$("#"+idPrefix+id+" .emoticon.nerdy").replaceWith("8|"):"";
            ($("#"+idPrefix+id+" .emoticon.angry").length > 0)?$("#"+idPrefix+id+" .emoticon.angry").replaceWith(":@"):"";
            ($("#"+idPrefix+id+" .emoticon.fail").length > 0)?$("#"+idPrefix+id+" .emoticon.fail").replaceWith("(fail)"):"";
            ($("#"+idPrefix+id+" .emoticon.fml").length > 0)?$("#"+idPrefix+id+" .emoticon.fml").replaceWith("(fml)"):"";
            ($("#"+idPrefix+id+" .emoticon.brokenHeart").length > 0)?$("#"+idPrefix+id+" .emoticon.brokenHeart").replaceWith("&lt;/3"):"";
            ($("#"+idPrefix+id+" .emoticon.sleepy").length > 0)?$("#"+idPrefix+id+" .emoticon.sleepy").replaceWith("(zzz)"):"";
            ($("#"+idPrefix+id+" .emoticon.blackberry").length > 0)?$("#"+idPrefix+id+" .emoticon.blackberry").replaceWith("(bb)"):"";
            ($("#"+idPrefix+id+" .emoticon.facebook").length > 0)?$("#"+idPrefix+id+" .emoticon.facebook").replaceWith("(facebook)"):"";
            ($("#"+idPrefix+id+" .emoticon.youtube").length > 0)?$("#"+idPrefix+id+" .emoticon.youtube").replaceWith("(youtube)"):"";
            ($("#"+idPrefix+id+" .emoticon.sh").length > 0)?$("#"+idPrefix+id+" .emoticon.sh").replaceWith("(sh)"):"";
            ($("#"+idPrefix+id+" .emoticon.skype").length > 0)?$("#"+idPrefix+id+" .emoticon.skype").replaceWith("(skype)"):"";
            ($("#"+idPrefix+id+" .emoticon.twitter").length > 0)?$("#"+idPrefix+id+" .emoticon.twitter").replaceWith("(twitter)"):"";
            ($("#"+idPrefix+id+" .emoticon.mp3").length > 0)?$("#"+idPrefix+id+" .emoticon.mp3").replaceWith("(mp3)"):"";
        },
        
        // beautiful function, converts emoticon text to markup, why do it on the server side when the client has a CPU ;)
        // nav: O.textToEmo();
        textToEmo : function(text){
            
            return text.replace(/:P/ig,'<span title=":P" class="emoticon tongue"></span>')
            .replace(/:D/ig,'<span title=":D" class="emoticon laugh"></span>')
            .replace(/:\)/g,'<span title=":)" class="emoticon smile"></span>')
            .replace(/:\(/g,'<span title=":(" class="emoticon sad"></span>')
            .replace(/:'\(/g,'<span title=":\'(" class="emoticon cry"></span>')
            .replace(/&lt;3/g,'<span title="&lt;3" class="emoticon heart"></span>')
            .replace(/:O/g,'<span title=":O" class="emoticon surprised"></span>')
            .replace(/\(a\)/g,'<span title="(a)" class="emoticon angel"></span>')
            .replace(/:\$/g,'<span title=":$" class="emoticon blush"></span>')
            .replace(/\(cool\)/g,'<span title="(cool)" class="emoticon cool"></span>')
            .replace(/\B;\)/g,'<span title=";)" class="emoticon wink"></span>')
            .replace(/8\|/g,'<span title="8|" class="emoticon nerdy"></span>')
            .replace(/:S/ig,'<span title=":S" class="emoticon worried"></span>')
            .replace(/:\|/g,'<span title=":|" class="emoticon speechless"></span>')
            .replace(/\(xD\)/g,'<span title="(xD)" class="emoticon hardLaugh"></span>')
            .replace(/\(xP\)/g,'<span title="(xP)" class="emoticon hardLaughTongue"></span>')
            .replace(/\B;D/ig,'<span title=";D" class="emoticon winkLaugh"></span>')
            .replace(/\B;P/ig,'<span title=";P" class="emoticon winkTongue"></span>')
            .replace(/:@/g,'<span title=":@" class="emoticon angry"></span>')
            .replace(/\(fail\)/g,'<span title="(fail)" class="emoticon fail"></span>')
            .replace(/\(banghead\)/g,'<span title="(banghead)" class="emoticon headBang"></span>')
            .replace(/\(fml\)/g,'<span title="(fml)" class="emoticon fml"></span>')
            .replace(/&lt;\/3/g,'<span title="&lt;/3" class="emoticon brokenHeart"></span>')
            .replace(/\(zzz\)/g,'<span title="(zzz)" class="emoticon sleepy"></span>')
            .replace(/\(bb\)/g,'<span title="(bb)" class="emoticon blackberry"></span>')
            .replace(/\(facebook\)/g,'<span title="(facebook)" class="emoticon facebook"></span>')
            .replace(/\(msn\)/g,'<span title="(msn)" class="emoticon msn"></span>')
            .replace(/\(youtube\)/g,'<span title="(youtube)" class="emoticon youtube"></span>')
            .replace(/\(sh\)/g,'<span title="(sh)" class="emoticon sh"></span>')
            .replace(/\(skype\)/g,'<span title="(skype)" class="emoticon skype"></span>')
            .replace(/\(twitter\)/g,'<span title="(twitter)" class="emoticon twitter"></span>')
            .replace(/\(mp3\)/g,'<span title="(mp3)" class="emoticon mp3"></span>');

        },
        // nav: O.emoticonTable();
        emoticonTable: function(){

            $('div.table.emoticons').hide();

            $('div#insertEmoticonBtn').click(function(){
                $('div.table.emoticons').toggle();
            });

            $('#editor').focus(function(){
                $('div.table.emoticons').hide();
            });

            $("span.emoticon").mouseout(function(event){
                $("div.emoticonName").html("");
                $("div.emoticonVal").html("");
            });
            
            $('body').click(function(){
               $("div.table.emoticons").hide();
            })
                            
            $("div.table.emoticons span.emoticon").click(function(event){
                
                $("div.table.emoticons").hide();

                if(window.scapePublisher){
                window.scapePublisher.setEditorHTML(window.scapePublisher.getEditorHTML()+ " " + $("div.emoticonVal").html());
                }else if(window.replyPublisher){
                window.replyPublisher.setEditorHTML(window.replyPublisher.getEditorHTML()+ " " + $("div.emoticonVal").html());   
                }

            });

            $("span.emoticon.wink").hover(function(event){
                $("div.emoticonName").html("Wink");
                $("div.emoticonVal").html(";)");
            });

            $("span.emoticon.winkLaugh").hover(function(event){
                $("div.emoticonName").html("Laughing wink");
                $("div.emoticonVal").html(";D");
            });

            $("span.emoticon.winkTongue").hover(function(event){
                $("div.emoticonName").html("Wink with tongue out");
                $("div.emoticonVal").html(";P");
            });

            $("span.emoticon.cry").hover(function(event){
                $("div.emoticonName").html("Crying");
                $("div.emoticonVal").html(":'(");
            });

            $("span.emoticon.sad").hover(function(event){
                $("div.emoticonName").html("Sad");
                $("div.emoticonVal").html(":(");
            });

            $("span.emoticon.angel").hover(function(event){
                $("div.emoticonName").html("Angel");
                $("div.emoticonVal").html("(a)");
            });

            $("span.emoticon.blush").hover(function(event){
                $("div.emoticonName").html("Embarassed");
                $("div.emoticonVal").html(":$");
            });

            $("span.emoticon.cool").hover(function(event){
                $("div.emoticonName").html("Cool");
                $("div.emoticonVal").html("(cool)");
            });

            $("span.emoticon.heart").hover(function(event){
                $("div.emoticonName").html("Heart");
                $("div.emoticonVal").html("&lt;3");
            });

            $("span.emoticon.brokenHeart").hover(function(event){
                $("div.emoticonName").html("Broken heart");
                $("div.emoticonVal").html("&lt;/3");
            });

            $("span.emoticon.nerdy").hover(function(event){
                $("div.emoticonName").html("Nerdy");
                $("div.emoticonVal").html("8|");
            });

            $("span.emoticon.sleepy").hover(function(event){
                $("div.emoticonName").html("Sleepy");
                $("div.emoticonVal").html("(zzz)");
            });

            $("span.emoticon.surprised").hover(function(event){
                $("div.emoticonName").html("Surprised");
                $("div.emoticonVal").html(":O");
            });

            $("span.emoticon.smile").hover(function(event){
                $("div.emoticonName").html("Smiling");
                $("div.emoticonVal").html(":)");
            });

            $("span.emoticon.angry").hover(function(event){
                $("div.emoticonName").html("Angry");
                $("div.emoticonVal").html(":@");
            });

            $("span.emoticon.speechless").hover(function(event){
                $("div.emoticonName").html("Speechless");
                $("div.emoticonVal").html(":|");
            });

            $("span.emoticon.laugh").hover(function(event){
                $("div.emoticonName").html("Laughing");
                $("div.emoticonVal").html(":D");
            });

            $("span.emoticon.sh").hover(function(event){
                $("div.emoticonName").html("Scapehouse");
                $("div.emoticonVal").html("(sh)");
            });

            $("span.emoticon.facebook").hover(function(event){
                $("div.emoticonName").html("Facebook");
                $("div.emoticonVal").html("(facebook)");
            });

            $("span.emoticon.msn").hover(function(event){
                $("div.emoticonName").html("MSN");
                $("div.emoticonVal").html("(msn)");
            });

            $("span.emoticon.tongue").hover(function(event){
                $("div.emoticonName").html("Tongue out");
                $("div.emoticonVal").html(":P");
            });

            $("span.emoticon.worried").hover(function(event){
                $("div.emoticonName").html("Confused");
                $("div.emoticonVal").html(":S");
            });

            $("span.emoticon.headBang").hover(function(event){
                $("div.emoticonName").html("Banging Head");
                $("div.emoticonVal").html("(banghead)");
            });

            $("span.emoticon.skype").hover(function(event){
                $("div.emoticonName").html("Skype");
                $("div.emoticonVal").html("(skype)");
            });

            $("span.emoticon.twitter").hover(function(event){
                $("div.emoticonName").html("Twitter");
                $("div.emoticonVal").html("(twitter)");
            });

            $("span.emoticon.hardLaugh").hover(function(event){
                $("div.emoticonName").html("Hard laugh");
                $("div.emoticonVal").html("(xD)");
            });

            $("span.emoticon.hardLaughTongue").hover(function(event){
                $("div.emoticonName").html("Hard laugh with tongue out");
                $("div.emoticonVal").html("(xP)");
            });

            $("span.emoticon.youtube").hover(function(event){
                $("div.emoticonName").html("YouTube");
                $("div.emoticonVal").html("(youtube)");
            });

            $("span.emoticon.blackberry").hover(function(event){
                $("div.emoticonName").html("BlackBerry");
                $("div.emoticonVal").html("(bb)");
            });

            $("span.emoticon.mp3").hover(function(event){
                $("div.emoticonName").html("MP3 player");
                $("div.emoticonVal").html("(mp3)");
            });

        }
        
    }
    // Do it the JQuery style ;).
    window.O = O;
}(jQuery));
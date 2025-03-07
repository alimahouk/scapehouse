// Watchlist funcitons

function ajaxWatch(watchid,targetid,location){ 

    if(location == "watchlist"){
        $.ajax({
            type:"POST",
            url: "/logged/ajax/watch",
            data: "targetid=" + targetid,
            success: function(){
                $("#W"+watchid+" .watchOptions").html("<div class='watchStatus'><span class='UIIcon px16 tick'></span>Watching</div>");
            }
        });
    }else if(location == "userListWindow"){
        $.ajax({
            type:"POST",
            url: "/logged/ajax/watch",
            data: "targetid=" + targetid,
            success: function(){
                $("#LI"+watchid+" .watchOptions").html("<div class='watchStatus'><span class='UIIcon px16 tick'></span>Watching</div>");
            }
        });
    }else{
        $.ajax({
            type:"POST",
            url: "/logged/ajax/watch",
            data: "targetid=" + targetid,
            success: function(){
                $(".watchOptions").html("<div class='watchStatus'><span class='UIIcon px16 tick'></span>Watching</div>");
            }
        });
    }

}
    

function ajaxIgnore(watchid,location){
    if(location == "profile"){

        $.ajax({
            type:"POST",
            url: "/logged/ajax/ignore",
            data: "watchid=" + watchid + "&profile=" + 1,
            success: function(){

                window.location.reload();
            //                html = $("#ignoreBtn").html();
            //                if (html == "<a>Ignore</a>" || html == "<A>Ignore</A>"){
            //                    html = $("#ignoreBtn").html("<a>Undo ignore</a>");
            //                } else {
            //                    html = $("#ignoreBtn").html("<a>Ignore</a>");
            //                }
            }
        });
    
    }
    if(location == "watchlist"){

        $.ajax({
            type:"POST",
            url: "/logged/ajax/ignore",
            data: "watchid=" + watchid,
            success: function(){

                $("#W"+watchid).remove();
            //                html = $("#W"+watchid+" .ignoreBtn").html();
            //                if (html == "Ignore"){
            //                    html = $("#W"+watchid+" .ignoreBtn").html("Undo ignore");
            //                } else {
            //                    html = $("#W"+watchid+" .ignoreBtn").html("Ignore");
            //                }
            }
        });
    
    }
    

}
function ajaxDisplayWatchlist(show,userid,userName,sessionUser){

    if (show == "watchers"){

        $.ajax({
            type:"POST",
            url: "/logged/ajax/displaywatchlist",
            data: "watchers=" + show + "&userid=" + userid,
            success: function(data){

                $("#watchlist").html(data);

                if(sessionUser == true){

                    $(".contentFilters").html(
                        "<a onclick='ajaxDisplayWatchlist(\"watching\","+userid+",\""+userName+"\","+sessionUser+");'>People I'm watching</a>&nbsp;|&nbsp;<span class='activeFilter'>People watching me</span>"
                        );
                }else {
                    $(".contentFilters").html(
                        "<a onclick='ajaxDisplayWatchlist(\"watching\","+userid+",\""+userName+"\","+sessionUser+");'>Watching</a>&nbsp;|&nbsp;<span class='activeFilter'>Watchers</span>"
                        );
                }
                $("#designoscape").addClass("watchers");
                $("#designoscape").removeClass("watching");

            }
        });

    } else {

        $.ajax({
            type:"POST",
            url: "/logged/ajax/displaywatchlist",
            data: "userid=" + userid,
            success: function(data){

                $("#watchlist").html(data);
                if (show == "watching"){

                    if(sessionUser == true){
                        $(".contentFilters").html(
                            "<span class='activeFilter'>People I'm watching</span>&nbsp;|&nbsp;<a onclick='ajaxDisplayWatchlist(\"watchers\","+userid+",\""+userName+"\","+sessionUser+");'>People watching me</a>"
                            );
                    } else {
                        $(".contentFilters").html(
                            "<span class='activeFilter'>Watching</span>&nbsp;|&nbsp;<a onclick='ajaxDisplayWatchlist(\"watchers\","+userid+",\""+userName+"\","+sessionUser+");'>Watchers</a>"
                            );
                    }
                    $("#designoscape").addClass("watching");
                    $("#designoscape").removeClass("watchers");
                }
            }
        });
    }
}

function ajaxWatchlistSearch(query,userid,type){
    
    $.ajax({
        type:"POST",
        url: "/logged/ajax/watchlistsearch",
        data: "query=" + query + "&userid=" + userid + "&type=" + type,
        success: function(data){
            $("#watchlistResults").html(data);
        }
    });
}
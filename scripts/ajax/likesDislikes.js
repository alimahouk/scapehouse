//Like ---------------------
// The l and d variables act as context setter flags, once the request hits the controller, it assists
// the controller to determine that the request is for a like or a dislike.
function ajaxLike(elmid,idType){

    // Sends a request to create a like on the chosen element.
    idPrefix = prefixConstruct(idType);

    $.ajax({
        type:"POST",
        url: "/logged/ajax/likesdislikes",
        data: "elmid=" + elmid + "&idType=" + idType + "&l=1",
        success: function(html){


            $("#"+idPrefix+elmid+" .likesAndDislikes").html(html);
            $("#"+idPrefix+elmid+" .userOpinions").hide();
            $("#"+idPrefix+elmid+" .userOpinions").fadeIn("fast");


            var content = $("#"+idPrefix+elmid+" .likeBtn").html();
            if (content == "<a>Like</a>" || content == "<A>Like</A>"){
                $("#"+idPrefix+elmid+" .likeBtn").html("<a>Remove like</a>");

            }
            if (content == "<a>Remove like</a>" || content == "<A>Remove like</A>"){
                $("#"+idPrefix+elmid+" .likeBtn").html("<a>Like</a>");

            }
            var contentOfDislike = $("#"+idPrefix+elmid+" .dislikeBtn").html();
            if (contentOfDislike == "<a>Remove dislike</a>" || contentOfDislike == "<A>Remove dislike</A>")
            {
                $("#"+idPrefix+elmid+" .dislikeBtn").html("<a>Dislike</a>");
            }

        }
    });

}
//like end ---------------------

// Dislike ---------------------

function ajaxDislike(elmid,idType){

    // Sends a request to create a dislike on the chosen element.

    idPrefix = prefixConstruct(idType);

    $.ajax({
        type:"POST",
        url: "/logged/ajax/likesdislikes",
        data: "elmid=" + elmid + "&idType=" + idType + "&d=1",
        success: function(html){


            $("#"+idPrefix+elmid+" .likesAndDislikes").html(html);
            $("#"+idPrefix+elmid+" .userOpinions").hide();
            $("#"+idPrefix+elmid+" .userOpinions").fadeIn("fast");



            var content = $("#"+idPrefix+elmid+" .dislikeBtn").html();
            if (content == "<a>Dislike</a>" || content == "<A>Dislike</A>"){
                $("#"+idPrefix+elmid+" .dislikeBtn").html("<a>Remove dislike</a>");

            }
            if (content == "<a>Remove dislike</a>" || content == "<A>Remove dislike</A>"){
                $("#"+idPrefix+elmid+" .dislikeBtn").html("<a>Dislike</a>");

            }
            var contentOfLike = $("#"+idPrefix+elmid+" .likeBtn").html();
            if (contentOfLike == "<a>Remove like</a>" || contentOfLike == "<A>Remove like</A>")
            {
                $("#"+idPrefix+elmid+" .likeBtn").html("<a>Like</a>");
            }

        }
    });

}

//Dislike end ---------------------

function displayList(elmid,idType,like,dislike){ //alert(elmid);alert(idType);alert(like);alert(dislike);

    $('#userOpinionsWindow').fadeIn();
    $('#pageOverlay').show();

    if(like){
        $('#likesDislikesWindowTitle').html("People who like this");
    }
    if(dislike){
        $('#likesDislikesWindowTitle').html("People who dislike this");
    }
    $.ajax({
        type:"POST",
        url: "/logged/ajax/likedislikelist",
        data: "elmid=" + elmid + "&idType=" + idType + "&l="+ like + "&d=" + dislike,
        success: function(html){
           
        $("#likesDislikesList").html(html);

        }
    });
    
}
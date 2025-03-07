//$(document).ready(function() {
//    $('div.table.emoticons').hide();
//
//    $('div#insertEmoticonBtn').click(function(){
//        $('div.table.emoticons').toggle();
//    });
//
//    $('#editor').focus(function(){
//        $('div.table.emoticons').hide();
//    });
//
//    $("span.emoticon").mouseout(function(event){
//        $("div.emoticonName").html("");
//        $("div.emoticonVal").html("");
//    });
//
//    $("div.table.emoticons span.emoticon").click(function(event){
//
//        if ($("#editor").html() == "" || $("#editor").html() == "<br>" || $("#editor").html() == "<BR>") {
//            $("#editor").html("");
//        }
//        $("#editor").html( $("#editor").html()+ "&nbsp;" + $("div.emoticonVal").html());
//    });
//
//    $("span.emoticon.wink").hover(function(event){
//        $("div.emoticonName").html("Wink");
//        $("div.emoticonVal").html(";)");
//    });
//
//    $("span.emoticon.winkLaugh").hover(function(event){
//        $("div.emoticonName").html("Laughing wink");
//        $("div.emoticonVal").html(";D");
//    });
//
//    $("span.emoticon.winkTongue").hover(function(event){
//        $("div.emoticonName").html("Wink with tongue out");
//        $("div.emoticonVal").html(";P");
//    });
//
//    $("span.emoticon.cry").hover(function(event){
//        $("div.emoticonName").html("Crying");
//        $("div.emoticonVal").html(":'(");
//    });
//
//    $("span.emoticon.sad").hover(function(event){
//        $("div.emoticonName").html("Sad");
//        $("div.emoticonVal").html(":(");
//    });
//
//    $("span.emoticon.angel").hover(function(event){
//        $("div.emoticonName").html("Angel");
//        $("div.emoticonVal").html("(a)");
//    });
//
//    $("span.emoticon.blush").hover(function(event){
//        $("div.emoticonName").html("Embarassed");
//        $("div.emoticonVal").html(":$");
//    });
//
//    $("span.emoticon.cool").hover(function(event){
//        $("div.emoticonName").html("Cool");
//        $("div.emoticonVal").html("(cool)");
//    });
//
//    $("span.emoticon.heart").hover(function(event){
//        $("div.emoticonName").html("Heart");
//        $("div.emoticonVal").html("&lt;3");
//    });
//
//    $("span.emoticon.brokenHeart").hover(function(event){
//        $("div.emoticonName").html("Broken heart");
//        $("div.emoticonVal").html("&lt;/3");
//    });
//
//    $("span.emoticon.nerdy").hover(function(event){
//        $("div.emoticonName").html("Nerdy");
//        $("div.emoticonVal").html("8|");
//    });
//
//    $("span.emoticon.sleepy").hover(function(event){
//        $("div.emoticonName").html("Sleepy");
//        $("div.emoticonVal").html("(zzz)");
//    });
//
//    $("span.emoticon.surprised").hover(function(event){
//        $("div.emoticonName").html("Surprised");
//        $("div.emoticonVal").html(":O");
//    });
//
//    $("span.emoticon.smile").hover(function(event){
//        $("div.emoticonName").html("Smiling");
//        $("div.emoticonVal").html(":)");
//    });
//
//    $("span.emoticon.angry").hover(function(event){
//        $("div.emoticonName").html("Angry");
//        $("div.emoticonVal").html(":@");
//    });
//
//    $("span.emoticon.speechless").hover(function(event){
//        $("div.emoticonName").html("Speechless");
//        $("div.emoticonVal").html(":|");
//    });
//
//    $("span.emoticon.laugh").hover(function(event){
//        $("div.emoticonName").html("Laughing");
//        $("div.emoticonVal").html(":D");
//    });
//
//    $("span.emoticon.ds").hover(function(event){
//        $("div.emoticonName").html("Scapehouse");
//        $("div.emoticonVal").html("(ds)");
//    });
//
//    $("span.emoticon.facebook").hover(function(event){
//        $("div.emoticonName").html("Facebook");
//        $("div.emoticonVal").html("(facebook)");
//    });
//
//    $("span.emoticon.msn").hover(function(event){
//        $("div.emoticonName").html("MSN");
//        $("div.emoticonVal").html("(msn)");
//    });
//
//    $("span.emoticon.tongue").hover(function(event){
//        $("div.emoticonName").html("Tongue out");
//        $("div.emoticonVal").html(":P");
//    });
//
//    $("span.emoticon.worried").hover(function(event){
//        $("div.emoticonName").html("Confused");
//        $("div.emoticonVal").html(":S");
//    });
//
//    $("span.emoticon.headBang").hover(function(event){
//        $("div.emoticonName").html("Banging Head");
//        $("div.emoticonVal").html("(banghead)");
//    });
//
//    $("span.emoticon.skype").hover(function(event){
//        $("div.emoticonName").html("Skype");
//        $("div.emoticonVal").html("(skype)");
//    });
//
//    $("span.emoticon.twitter").hover(function(event){
//        $("div.emoticonName").html("Twitter");
//        $("div.emoticonVal").html("(twitter)");
//    });
//
//    $("span.emoticon.hardLaugh").hover(function(event){
//        $("div.emoticonName").html("Hard laugh");
//        $("div.emoticonVal").html("(xD)");
//    });
//
//    $("span.emoticon.hardLaughTongue").hover(function(event){
//        $("div.emoticonName").html("Hard laugh with tongue out");
//        $("div.emoticonVal").html("(xP)");
//    });
//
//    $("span.emoticon.youtube").hover(function(event){
//        $("div.emoticonName").html("YouTube");
//        $("div.emoticonVal").html("(youtube)");
//    });
//
//    $("span.emoticon.blackberry").hover(function(event){
//        $("div.emoticonName").html("BlackBerry");
//        $("div.emoticonVal").html("(bb)");
//    });
//
//    $("span.emoticon.mp3").hover(function(event){
//        $("div.emoticonName").html("MP3 player");
//        $("div.emoticonVal").html("(mp3)");
//    });
//});
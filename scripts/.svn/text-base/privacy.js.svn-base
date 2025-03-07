$(document).ready(function() {

    $("#maxPrivacy").bind("click",function(){

        if($(this).is(":checked")){

            $("#warning").stop(true,true).slideUp();
            $("#radio_hideFrom").attr("disabled", "");


            $('select').each(function(){

                if($(this).val() == 1){
                    $(this).append('<option class="watchersOnly" value="3" selected="selected">Watchers only</option>');
                }else{
                    $(this).append('<option class="watchersOnly" value="3">Watchers only</option>');
                }
            });
        }else{

            $('.watchersOnly').remove();
            $("#warning").stop(true,true).slideDown();

            $("#radio_hideFrom").attr("disabled", "disabled");

        }
    });
    
//    $("select").bind("change",function(){
//        (this.options[this.selectedIndex].value == 2)? showFilter($(this).attr('id')):$("#"+$(this).attr('id')+"EditBtn").hide();
//    });



});
function save(id){ 

    hide = new Array();
    show = new Array();
    
    if($("#radio_hideFrom").is(":checked")){
        $("#privacyList div").each(function(){
            hide.push($(this).attr("id"));
        });
    }else if($("#radio_showTo").is(":checked")){
        $("#privacyList div").each(function(){
            show.push($(this).attr("id"));
        });
    }
    $("#"+id+"EditBtn").show();
    ajaxSavePrivacy(id,show,hide);
}

function showFilter(id){

    ajaxGetPrivacy(id);
    
    switch(id){
        // General info -----
        case "genInfo":
            $("#detailName").html("General Info");
            $("#privacyFilters").attr("type","genInfo");
            break;
        case "genLoc":
            $("#detailName").html("General Info : Location");
            $("#privacyFilters").attr("type","genLoc");
            break;
        case "genNick":
            $("#detailName").html("General Info : Nickname");
            $("#privacyFilters").attr("type","genNick");
            break;
        case "genLoc":
            $("#genSex").html("General Info : Sex");
            $("#privacyFilters").attr("type","genLoc");
            break;
        case "genbday":
            $("#detailName").html("General Info : Birthday");
            $("#privacyFilters").attr("type","genbday");
            break;

        //Personal info -----
        case "perInfo":
            $("#detailName").html("Personal Info");
            $("#privacyFilters").attr("type","perInfo");
            break;
        case "perAbout":
            $("#detailName").html("Personal Info : Something about me");
            $("#privacyFilters").attr("type","perAbout");
            break;
        case "perBook":
            $("#detailName").html("Personal Info : Favorite books");
            $("#privacyFilters").attr("type","perBook");
            break;
        case "perGame":
            $("#detailName").html("Personal Info : Favorite games");
            $("#privacyFilters").attr("type","perGame");
            break;
        case "perShow":
            $("#detailName").html("Personal Info : Favorite TV shows");
            $("#privacyFilters").attr("type","perShow");
            break;
        case "perMovie":
            $("#detailName").html("Personal Info : Favorite movies");
            $("#privacyFilters").attr("type","perMovie");
            break;
        case "perInterest":
            $("#detailName").html("Personal Info : Interests");
            $("#privacyFilters").attr("type","perInterest");
            break;
        case "perNeeds":
            $("#detailName").html("Personal Info : Things I can't live without");
            $("#privacyFilters").attr("type","perNeeds");
            break;

        // Contact info -------

        case "conInfo":
            $("#detailName").html("Contact Info");
            $("#privacyFilters").attr("type","conInfo");
            break;
        case "conEmail":
            $("#detailName").html("Contact Info : Email");
            $("#privacyFilters").attr("type","conEmail");
            break;
        case "conIm":
            $("#detailName").html("Contact Info : IM");
            $("#privacyFilters").attr("type","conIm");
            break;
        case "conFax":
            $("#detailName").html("Contact Info : Fax");
            $("#privacyFilters").attr("type","conFax");
            break;
        case "conHome":
            $("#detailName").html("Contact Info : Home telephone");
            $("#privacyFilters").attr("type","conHome");
            break;
        case "conOffice":
            $("#detailName").html("Contact Info : Office telephone");
            $("#privacyFilters").attr("type","conOffice");
            break;
        case "conMobile":
            $("#detailName").html("Contact Info : Mobile number");
            $("#privacyFilters").attr("type","conMobile");
            break;
        case "conBB":
            $("#detailName").html("Contact Info : BlackBerry PIN");
            $("#privacyFilters").attr("type","conBB");
            break;
        case "conWebsite":
            $("#detailName").html("Contact Info : Websites");
            $("#privacyFilters").attr("type","conWebsite");
            break;

        // Education info -------

        case "eduInfo":
            $("#detailName").html("Education info");
            $("#privacyFilters").attr("type","eduInfo");
            break;
        case "eduSchool":
            $("#detailName").html("Education info : High School");
            $("#privacyFilters").attr("type","eduSchool");
            break;
        case "eduUni":
            $("#detailName").html("Education info : College/University");
            $("#privacyFilters").attr("type","eduUni");
            break;
        case "eduMajor":
            $("#detailName").html("Education info : Major");
            $("#privacyFilters").attr("type","eduMajor");
            break;

        // Work info -----

        case "workInfo":
            $("#detailName").html("Work info");
            $("#privacyFilters").attr("type","workInfo");
            break;
        case "workCompany":
            $("#detailName").html("Work info : Company");
            $("#privacyFilters").attr("type","workCompany");
            break;
        case "workPos":
            $("#detailName").html("Work info : Position");
            $("#privacyFilters").attr("type","workPos");
            break;
        case "workSince":
            $("#detailName").html("Work info : Working there since");
            $("#privacyFilters").attr("type","workSince");
            break;
        case "workAbout":
            $("#detailName").html("Work info : Company description");
            $("#privacyFilters").attr("type","workAbout");
            break;

        // Pictures ----

        case "gallery":
            $("#detailName").html("Who can see your photo gallery?");
            $("#privacyFilters").attr("type","gallery");
            break;

        // Scapes -----
        case "scape":
            $("#detailName").html("Who can see the scapes you publish?");
            $("#privacyFilters").attr("type","scape");
            break;
    }

    $("#privacyFilters").fadeIn();
    $("#pageOverlay").show();

}
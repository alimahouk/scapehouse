<?php
class Logged_Model_Timeline {

    private $output;

    static function display($scape,$type=NULL) {

        if(!empty($scape)) {

            $session = Model_StaticFunctions::sessionContent();
            
            $processor = new self;
            $processor->processContent($scape)
                    ->processReplies($scape)
                    ->processLikesDislikes($scape)
                    ->processThumbs($scape)
                    ->processTime($scape)
                    ->processUserThumb($scape,$type)
                    ->processTitle($scape)
                    ->processUserActions($scape,$type);

            $processor->output["url"] = "/{$scape["username"]}/scape/{$scape["id"]}";

            if(!$GLOBALS["session"]){
                $loginJs = "$('#sidebarLoginTooltip').show();$('#loginUsername').focus();return false;";
            }
            $output = $processor->output;

            if($type == "update") {
                $hidden = "hidden";
            }elseif($type == "scapebox"){
		$taggedScape = "taggedScape";

	}
	
	if($scape["private"] == 1){ // Private scape
	   // $privateScape = " ⋅ <span class=\"UIIcon px16 watchlist privacyEffect\" title=\"This scape can only be seen by tagged people\"></span>";
	}
	
	$receiverids = json_decode($scape["receiverids"]);
		$photo = new Logged_Model_Photo();
		$usersTable = new Model_DbTable_Users();
		if($receiverids){
		foreach($receiverids as $userid){
		    $user = $usersTable->getUserById($userid);
		    $imgSrc = $photo->display($userid,"user","small");
		    $tagDisplay .= '<a href="/'.$user["username"].'/profile" class="faceMashLink"><span class="tooltip top left hidden"><span class="tooltipTxt">'.$user["fullname"].'</span></span><img class="thmbnl" src="'.$imgSrc.'" alt="'.$user["fullname"].'" /></a>';
		}
	    }else{
		$tagsHidden = "hidden";
	    }
	    
	    if($scape["imp"] === 0){
		$unread = "unread";
	    }
	    
            return <<<MARKUP
	<div class="timelineEntry scape $taggedScape $unread $hidden" id="S{$scape["id"]}"> 
        <a class="UIButton grey readMore hidden" href="{$output["url"]}"><span class="UIButtonTxt">&raquo;&nbsp;Go to scape</span></a>
                    {$output["identification"]}
                                        <div class="entryBody">
                                                <div class="scapeBodyTxt">{$output["title"]}<div id="SB{$scape["id"]}">{$output["content"]}</div></div>
						
                                                        <script>
                                                        $(document).ready(function(){
                                                        $("#SB{$scape["id"]}").html(O.textToEmo($("#SB{$scape["id"]}").html()));
                                                        });
                                                        </script>					
                    {$output["thumbs"]}
                                                <span title="{$output["exactTime"]}" class="timestamp scape"><a href="{$output["url"]}">{$output["relativeTime"]}</a></span>{$privateScape}
                                            
					    <div class="tags $tagsHidden">
                                                    <div class="storyFacemash">
                                                            <div>
                                                           $tagDisplay
                                                           </div>
                                                    </div>
                                                    <div class="paperweight"></div>
                                            </div>
					    
					</div> </a>
					{$output["delConfirm"]}
                                        <div class="userActions">
         <a href="{$output["url"]}#publisher" class="replyBtn">Reply</a> &middot;
         <span onclick="{$loginJs}O.like.create('S{$scape["id"]}');" class="likeBtn">{$output["likeBtn"]}</span> &middot; <span onclick="{$loginJs}O.dislike.create('S{$scape["id"]}');" class="dislikeBtn">{$output["dislikeBtn"]}</span>
                    {$output["delBtn"]}<img class="spinner hidden" src="/graphics/en/UI/spinning_indicator.gif" style="margin-left: 10px;" />
         </div>
          <div class="likesAndDislikes" style="display:inline;">{$output["likesDislikes"]}</div>
                    {$output["replyCount"]}
	    </div>
            <script>
                $("#S{$scape["id"]} .faceMashLink").hover(
                function(){
		$('.tooltip',this).stop(true,true).show()
		},
                function(){
		$('.tooltip',this).stop(true,true).hide(
		)});
                $("#S{$scape["id"]} .faceMashLink .tooltip").mouseover(function(){
		$(this).hide()
		});
            </script>	    
MARKUP;


        }
    }

    private function processContent($scape) {

        $content = Model_StaticFunctions::stripHtmlTags($scape["content"], '<a>');

        if (mb_strlen($content,'UTF8') > 300) {
            $content = Model_StaticFunctions::trimWithoutImgAndLinks($content, 300, "<span class=\"readMoreBtn\" >... <a href=\"/{$scape["username"]}/scape/{$scape["id"]}\" >Read more</a></span>");
        }

        $this->output["content"] = $content;
        return $this;
    }

    private function processReplies($scape) {


        if ($scape["replyCount"] > 1) {
            $this->output["replyCount"] =
                    <<<REPLYCOUNT


		<a class="replies" href="/{$scape["username"]}/scape/{$scape["id"]}#replies"><span class="UIIcon px16 reply"></span>See all <span class="numberOfReplies">{$scape["replyCount"]}</span> replies.</a>

REPLYCOUNT;
        } elseif ($scape["replyCount"] == 1) {
            $this->output["replyCount"] =
                    <<<REPLYCOUNT


		<a class="replies" href="/{$scape["username"]}/scape/{$scape["id"]}#replies"><span class="UIIcon px16 reply"></span>See <span class="numberOfReplies">{$scape["replyCount"]}</span> reply.</a>

REPLYCOUNT;
        } else {
            $this->output["replyCount"] = NULL;
        }
        return $this;
    }

    private function processLikesDislikes($scape) {
        $likeAndDislike = new Logged_Model_LikeAndDislike();
        $likeAndDislike = $likeAndDislike->displayLikeAndDislike($scape["id"], 'scape');
        $this->output["likesDislikes"] = $likeAndDislike['display'];

        $this->output["likeBtn"] = "<a>Like</a>";
        $this->output["dislikeBtn"] = "<a>Dislike</a>";

        if ($likeAndDislike['Lyou'] || $likeAndDislike['LyouAndOther'] || $likeAndDislike['LyouAndNumOther']) {
            $this->output["likeBtn"] = "<a>Remove like</a>";
        }
        if ($likeAndDislike['Dyou'] || $likeAndDislike['DyouAndOther'] || $likeAndDislike['DyouAndNumOther']) {
            $this->output["dislikeBtn"] = "<a>Remove dislike</a>";
        }
        return $this;
    }

    private function processThumbs($scape) {
        if ($scape["content"] != "") {
            $dom = new DOMDocument();
            $dom->loadHTML($scape["content"]);
            $imgs = $dom->getElementsByTagName('img');

            unset($imgThumb1, $imgThumb2, $imgThumb3, $imgThumb4,$divsStart,$divsEnd);

            if ($imgs->length != 0) {

                $divsStart = "<table class=\"embeddedPhotos\"  id=\"embed{$scape["id"]}\" border=\"0\" cellspacing=\"0\"><tbody><tr>";

                foreach ($imgs as $img) {

                    foreach ($img->attributes as $attrName => $attrNode) {

                        if ($attrName == "src") {

                            if (!$imgThumb1) {
                                $imgThumb1 = "<td><a class='thmbnlImg' href='/{$scape["username"]}/scape/{$scape["id"]}' ><img class='CGImage' src='$attrNode->value' /></a></td>";
                            } elseif (!$imgThumb2) {
                                $imgThumb2 = "<td><a class='thmbnlImg' href='/{$scape["username"]}/scape/{$scape["id"]}' ><img class='CGImage' src='$attrNode->value' /></a></td>";
                            } elseif (!$imgThumb3) {
                                $imgThumb3 = "<td><a class='thmbnlImg' href='/{$scape["username"]}/scape/{$scape["id"]}' ><img class='CGImage' src='$attrNode->value' /></a></td>";
                            } elseif (!$imgThumb4 && $this->location == "home") {
                                $imgThumb4 = "<td><a class='thmbnlImg' href='/{$scape["username"]}/scape/{$scape["id"]}' ><img class='CGImage' src='$attrNode->value' /></a></td>";
                            }
                        }
                    }
                }
                $divsEnd = "</tr></tbody></table>";
            }
            $this->output["thumbs"] =  $divsStart . $imgThumb1 . $imgThumb2 .$imgThumb3 . $imgThumb4 . $divsEnd;
        }
        return $this;
    }

    private function processTime($scape) {
        $this->output["relativeTime"] = Model_StaticFunctions::relativeTime(strtotime($scape["time"]));
        $this->output["exactTime"] = Model_StaticFunctions::formatDateFromTimestamp($scape["time"]);
        return $this;
    }

    private function processUserThumb($scape,$type=NULL) {

        if ($type == "home" || $type == "update" || $type == "scapebox") {

            $photo = new Logged_Model_Photo();
	    if(!$scape["fullname"]){
	    $scape["fullname"] = $scape["firstname"] . " " . $scape["lastname"];
	    }
            $this->output["identification"] = <<<MARKUP
                 <div class="identification">
         <a href="/{$scape["username"]}/profile" class="userThmbnlSmall" ><img src="{$photo->display($scape["userid"], "user", "small")}" class="userThmbnlSmall" width="47" height="47" /></a>
             <span class="userName entrySubject scapeAuthor"><a href="/{$scape["username"]}/profile">{$scape["fullname"]}</a></span>
                 </div>
MARKUP;
        }
        return $this;
    }

    private function processTitle($scape) {
	
	$scape["title"] = htmlspecialchars($scape["title"]);
        if($scape["title"]) {
            $this->output["title"] = "<h2 class='postTitle'><a href= \"/{$scape["username"]}/scape/{$scape["id"]}\">{$scape["title"]}</a></h2>";
        }
        return $this;
    }

    private function processUserActions($scape,$type) {
        $session = Model_StaticFunctions::sessionContent();
        $delBtn = NULL;

        if ($session->id == $scape["userid"] || $type == "scapebox") {
            $this->output["delBtn"] = <<<MARKUP
             &middot; <span class="deleteBtn" onclick="$('#S{$scape["id"]} .deleteConfirmation').slideDown();"><a>Delete</a></span>
MARKUP;
	
      if($type == "scapebox"){
	$fromYourScapebox = "from your Scapebox";
	$scapeBoxDelete = ",true";
      }
	    $this->output["delConfirm"] = <<<MARKUP
<div class="deleteConfirmation hidden">
Are you sure you want to delete this scape $fromYourScapebox?
         <div class="UIPanel">
          <a class="UIButton red confirmBtn" onclick="O.scape.deleteScape('{$scape["id"]}',false$scapeBoxDelete);"><span class="UIButtonTxt">Delete</span></a>
          <a class="UIButton grey cancelBtn" onclick="$('#S{$scape["id"]} .deleteConfirmation').slideUp();"><span class="UIButtonTxt">Cancel</span></a>
         </div>
</div>
MARKUP;
        }
        return $this;
    }

}
?>

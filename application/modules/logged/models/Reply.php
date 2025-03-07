<?php
class Logged_Model_Reply {

    private $output;

    static function display($reply,$type=FALSE,$scapeAuthorid=FALSE) {

        if(!empty($reply)) {

            $processor = new self;
            $processor->processContent($reply)
                    ->processComments($reply)
                    ->processLikesDislikes($reply)
                    ->processTime($reply)
                    ->processClassType($reply)
                    ->processUserActions($reply,$scapeAuthorid);
                    
            if(!$GLOBALS["session"]){
                $loginJs = "$('#sidebarLoginTooltip').show();$('#loginUsername').focus();return false;";
            }

            $output = $processor->output;

            if($type == "update") {
                $hidden = "style='display: none;'";
            }

            $photo = new Logged_Model_Photo();

            return <<<MARKUP

<div class="CGReply {$output["class"]}" {$hidden} id="R{$reply["id"]}">
    <div class="replyContent" >
                                <a href="/{$reply["username"]}/profile"><img src="{$photo->display($reply["userid"], "user", "small")}" class="authorThmbnlSmall" width="47" height="47" /></a>
                                <div class="replyBubbleArrow"></div>
                                <div class="replyBubble" style="width:340px;">
                                        <div class="replyBody">
                                                <div class="replyAuthor"><a href="/{$reply["username"]}/profile">{$reply["fullname"]}</a></div>

                    <!--{$output["editor"]}-->  <div id="RE{$reply["id"]}"></div>
                                                <div class="replyBodyTxt" style="width:auto;" id="RB{$reply["id"]}">{$output["content"]}</div>
                                              
                                                        <script>
                                                        $(document).ready(function(){
                                                        $("#RB{$reply["id"]}").html(O.textToEmo($("#RB{$reply["id"]}").html()));
                                                        });
                                                        </script>
                                                <span title="{$output["exactTime"]}" class="timestamp reply">{$output["relativeTime"]}</span>
                                        </div>
                    {$output["delConfirm"]}

                                <div class="likesAndDislikes">{$output["likesDislikes"]}</div>
                                </div>
                                </div>
                                <div class="replyActions">
                                        <span class="commentBtn reply" onclick="{$loginJs}O.animate.displayCommentBox('R{$reply["id"]}');"><a>Comment</a></span> &middot;
                                        <span class="likeBtn reply" onclick="{$loginJs}O.like.create('R{$reply["id"]}');">{$output["likeBtn"]}</span> &middot;
                                        <span class="dislikeBtn reply" onclick="{$loginJs}O.dislike.create('R{$reply["id"]}');">{$output["dislikeBtn"]}</span>
                    {$output["delEditBtn"]}<img class="spinner hidden" src="/graphics/en/UI/spinning_indicator.gif" style="position:absolute;margin-left: 10px; margin-top:3px;"/>
                                </div>
                                <div class="showAllComments">
                    {$output["commentCount"]}
                                </div>
                                <div class="bubbleArrowUp hidden commentComponent" style="display:none;"></div>
                                <div class="commentBox hidden commentComponent" style="height: 180px">
                                        <div class="minipublisher" >
                                                <div class="minieditor" id="minieditor{$reply["id"]}" style="min-height: 135px"></div>
                                        </div>

                                        <div class="postCommentBtn">
                                                <a class="UIButton post green commentComponent" onclick="O.comment.create('R{$reply["id"]}');"><span>Comment</span></a>
                                        </div>
                                 <div class="commentError hidden"><span class="UIIcon px16 cross"></span>An error occurred. Your comment was not posted.</div>
                                </div>
</div>
MARKUP;

        }
    }

    private function processContent($reply) {
        
        $strippedContent = strip_tags(preg_replace("/&nbsp;/"," ",$reply["content"]));
        if (mb_strlen($strippedContent,'UTF8') > 600) {
            $this->output["content"] = Model_StaticFunctions::trimWithoutImgAndLinks($strippedContent, 500, "... <a onclick=\"$('#RB{$reply["id"]}').hide().html($('#RB{$reply["id"]} .fullContent').html()).fadeIn();\">Show more</a>");
            $this->output["content"] .= "<div class='fullContent hidden'>{$reply["content"]}</div>";
        }else{
           $this->output["content"] = $reply["content"];
        }
        
        return $this;
    }

    private function processComments($reply) {

        switch(true) {
            case ($reply["commentCount"] == 0):

                $this->output["commentCount"] =  <<<MARKUP
            <a class="showAllCommentsBtnLink hidden" style="width:340px;" onclick="O.comment.getAllOnElm('R{$reply["id"]}')"></a>
MARKUP;
                break;

            case ($reply["commentCount"] == 1):

                $this->output["commentCount"] = <<<MARKUP

                  <a class="showAllCommentsBtnLink" style="width:340px;" onclick="O.comment.getAllOnElm('R{$reply["id"]}')"><span class="UIIcon px16 comment"></span>Show <span class="commentCount">{$reply["commentCount"]}</span> comment.</a>

MARKUP;
                break;

            case ($reply["commentCount"] > 1):

                $this->output["commentCount"] = <<<MARKUP

                  <a class="showAllCommentsBtnLink" style="width:340px;" onclick="O.comment.getAllOnElm('R{$reply["id"]}')"><span class="UIIcon px16 comment"></span>Show all <span class="commentCount">{$reply["commentCount"]}</span> comments.</a>

MARKUP;

                break;

        }
        return $this;

    }

    private function processLikesDislikes($reply) {
        $likeAndDislike = new Logged_Model_LikeAndDislike();
        $likeAndDislike = $likeAndDislike->displayLikeAndDislike($reply["id"], 'reply');
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

    private function processTime($reply) {
        $this->output["relativeTime"] = Model_StaticFunctions::relativeTime(strtotime($reply["time"]));
        $this->output["exactTime"] = Model_StaticFunctions::formatDateFromTimestamp($reply["time"]);
        return $this;
    }

    private function processClassType($reply) {

        $this->output["class"] = "CGOtherReply";

        if($reply["author"] == 1) {

            $this->output["class"] = "CGAuthorReply";
        }
        return $this;
    }

    private function processUserActions($reply,$scapeAuthorid=FALSE) {
        $session = Model_StaticFunctions::sessionContent();
        $delBtn = NULL;

        if ($GLOBALS["session"]->id == $reply["userid"]) {
            $this->output["delEditBtn"] = <<<MARKUP
&middot; <span class="editBtn reply" onclick="O.reply.edit('{$reply["id"]}');"><a class="editBtnLink">Edit</a></span> &middot;
<span class="deleteBtn reply" onclick="$('#R{$reply["id"]} .deleteConfirmation').slideDown();"><a>Delete</a></span>
MARKUP;

            $this->output["delConfirm"] = <<<MARKUP
<div class="deleteConfirmation hidden">
Are you sure you want to delete this reply?
         <div class="UIPanel">
          <a class="UIButton red confirmBtn" onclick="O.reply.deleteReply('{$reply["id"]}');"><span class="UIButtonTxt">Delete</span></a>
          <a class="UIButton grey cancelBtn" onclick="$('#R{$reply["id"]} .deleteConfirmation').slideUp();"><span class="UIButtonTxt">Cancel</span></a>
         </div>
</div>
MARKUP;

            $this->output["editor"] = <<<MARKUP
<div id="editReplyPublisher{$reply["id"]}" class="editReplyPublisher hidden">
<div  id="editReplyUpperPanel{$reply["id"]}" class="editReplyUpperPanel" ></div>
<form method="post">
<div  id="editReplyEditor{$reply["id"]}" class="editReplyEditor" contenteditable="true"></div>
</form>
</div>
MARKUP;
        }elseif($GLOBALS["session"]->id == $scapeAuthorid) {
            $this->output["delEditBtn"] = <<<MARKUP
&middot; <span class="deleteBtn reply" onclick="$('#R{$reply["id"]} .deleteConfirmation').slideDown();"><a>Delete</a></span>
MARKUP;
            $this->output["delConfirm"] = <<<MARKUP
<div class="deleteConfirmation hidden">
Are you sure you want to delete this reply?
         <div class="UIPanel">
          <a class="UIButton red confirmBtn" onclick="O.reply.deleteReply('{$reply["id"]}');O.animate.unlockUI();"><span class="UIButtonTxt">Delete</span></a>
          <a class="UIButton grey cancelBtn" onclick="$('#R{$reply["id"]} .deleteConfirmation').slideUp();"><span class="UIButtonTxt">Cancel</span></a>
         </div>
</div>
MARKUP;
        }
        return $this;
    }
}
?>

<?php
class Logged_Model_Comment {

    private $output;

    static function display($comment,$type=NULL,$elmAuthorid=FALSE) {

        if(!empty($comment)) {

            $processor = new self;
            $processor->processContent($comment)
                    ->processTime($comment)
                    ->processUserActions($comment,$elmAuthorid);

            $output = $processor->output;

            if($type == "update") {
                $hidden = "hidden";
            }

            $photo = new Logged_Model_Photo();

            return <<<MARKUP
{$output["editorInit"]}
<div class="comment $hidden" id="C{$comment["id"]}">
                    <div class="commentAuthorPicture"><a href="/{$comment["username"]}/profile"><img src="{$photo->display($comment["userid"], "user", "small")}" class="commentAuthorThmbnlSmall" alt="{$comment["fullname"]}" /></a></div>
                    <div class="commentBody">
                        <h3 class="authorName"><a href="/{$comment["username"]}/profile">{$comment["fullname"]}</a></h3>
                        {$output["editor"]}
                        <div class="commentTxt" id="CB{$comment["id"]}">{$output["content"]}</div>
                      <span title="{$output["exactTime"]}" class="timestamp comment">{$output["relativeTime"]}</span>
                    {$output["delEditBtn"]}
                    </div>
                    {$output["delConfirm"]}
                </div>
MARKUP;

        }
    }

    private function processContent($comment) {
        $this->output["content"] = Logged_Model_Publisher::addEmotions($comment["content"]);
        return $this;
    }

    private function processTime($comment) {
        $this->output["relativeTime"] = Model_StaticFunctions::relativeTime(strtotime($comment["time"]));
        $this->output["exactTime"] = Model_StaticFunctions::formatDateFromTimestamp($comment["time"]);
        return $this;
    }
    
    private function processUserActions($comment,$elmAuthorid) {
        $session = Model_StaticFunctions::sessionContent();
        $delBtn = NULL;

        if ($session->id == $comment["userid"]) {
            $this->output["delEditBtn"] = <<<MARKUP
<div style="display:inline;" class="commentActions"> &middot; <span class="editBtn comment" onclick="O.comment.edit('C{$comment["id"]}');"><a>Edit</a></span> &middot; <span class="deleteBtn comment" onclick="$('#C{$comment["id"]} .deleteConfirmation').slideDown();"><a>Delete</a></span></div>
MARKUP;
           $this->output["delConfirm"] = <<<MARKUP
<div class="deleteConfirmation hidden">
Are you sure you want to delete this comment?
         <div class="UIPanel">
          <a class="UIButton red confirmBtn" onclick="O.comment.deleteComment('{$comment["id"]}');"><span class="UIButtonTxt">Delete</span></a>
          <a class="UIButton grey cancelBtn" onclick="$('#C{$comment["id"]} .deleteConfirmation').slideUp();"><span class="UIButtonTxt">Cancel</span></a>
         </div>
</div>
MARKUP;
      $this->output["editorInit"] = <<<MARKUP
<script>
    editMinipublisher.setPanel("editMiniPanel{$comment["id"]}");
    editMinipublisher.addInstance("editMinieditor{$comment["id"]}");
</script>
MARKUP;
      $this->output["editor"] = <<<MARKUP
<div style="display:none;" id="editMiniPanel{$comment["id"]}" class="editMiniPanel"></div>
MARKUP;
        }elseif($session->id == $elmAuthorid){

            $this->output["delEditBtn"] = <<<MARKUP
<div style="display:inline;" class="commentActions"> &middot; <span class="deleteBtn comment" onclick="$('#C{$comment["id"]} .deleteConfirmation').slideDown();"><a>Delete</a></span></div>
MARKUP;
            
            $this->output["delConfirm"] = <<<MARKUP
<div class="deleteConfirmation hidden">
Are you sure you want to delete this comment?
         <div class="UIPanel">
          <a class="UIButton red confirmBtn" onclick="O.comment.deleteComment('{$comment["id"]}');"><span class="UIButtonTxt">Delete</span></a>
          <a class="UIButton grey cancelBtn" onclick="$('#C{$comment["id"]} .deleteConfirmation').slideUp();"><span class="UIButtonTxt">Cancel</span></a>
         </div>
</div>
MARKUP;
        }
        return $this;
    }

}
?>

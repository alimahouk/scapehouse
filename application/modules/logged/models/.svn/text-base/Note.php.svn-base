<?php
class Logged_Model_Note {
    static function display($note){
        
        switch ($note["color"]) {
        case 1: 
            $color = "yellow";
            break;
        case 2:
            $color = "pink";
            break;
        case 3:
            $color = "blue";
            break;
        case 4:
            $color = "green";
            break;
        }
        
        $photo = new Logged_Model_Photo();
        
      //  $noteReplyTable = new Logged_Model_DbTable_NoteReply();
      //  $noteReplies = $noteReplyTable->getNoteRepliesOnNote($note["id"]);
        
      //  $noteReplyCount = count($noteReplies);
        
      //  $noteReplyProcessor = new Logged_Model_NoteReply();
      //  if($noteReplies){       
      //  foreach($noteReplies as $noteReply){
      //      $noteReplyOutput .= $noteReplyProcessor->display($noteReply,$note["userid"]);
      //  }
   //  }      
        $fullname = $note["firstname"]. " " .$note["lastname"];
        $note["exactTime"] = Model_StaticFunctions::formatDateFromTimestamp($note["time"]);
        $note["relativeTime"] = Model_StaticFunctions::relativeTime(strtotime($note["time"]));
        $note["content"] = make_clickable(nl2br(htmlspecialchars($note["content"])));
        
        if($note["userid"] == $GLOBALS["session"]->id || $note["reciverid"] == $GLOBALS["session"]->id){
          $deleteBtn = '<a class="UIButton red deleteNote" onclick="O.note.deleteNote(\'M'.$note["id"].'\');"><span class="UIButtonTxt">Delete Note</span></a>';  
        }
        
        if($noteReplyCount != 0){
            $noteReplyCounter = "<span class=\"noteReplyCounter\"><a onclick=\"$('#MR{$note["id"]}').slideDown('fast')\" ><span class=\"UIIcon px16 reply\"></span>{$noteReplyCount}</a></span> · ";
        }
        
        return <<<MARKUP
                            <div class="note $color" id="M{$note["id"]}">
                                <div class="notePin"></div>
                                <div class="noteBody">
                                        <span class="noteContent">{$note["content"]}</span>
                                        <div class="noteInfo">
                                        <span class="noteTimestamp" title="{$note["exactTime"]}">{$note["relativeTime"]}</span><!-- ·
                                        $noteReplyCounter 
                                        <span class="noteActions"><a onclick="$('#MR{$note["id"]}').slideDown('fast');$('#MR{$note["id"]} textarea').focus();">Pin a reply</a></span>  -->  
                                        </div>                                      
                                </div>
                                $deleteBtn
                                <a class="userInfo" href="/{$note["username"]}/profile#/#noteboardView">
                                        <img class="thmbnl" src="{$photo->display($note["userid"],"user","small")}"/>
                                        <span class="userName">{$fullname}</span>
                                        <span class="username">{$note["username"]}</span>
                                </a>
                        </div>
                <div class="noteReplies $color hidden" id="MR{$note["id"]}">
                 <div class="noteReplyContainer">
                    $noteReplyOutput
                 </div>
                    <div class="noteReply replyBox $color">
                            <textarea placeholder="Pin a reply..." style="height: 26px;"></textarea>
                            <a class="UIButton green leaveNoteReply" onclick="O.note.createReply('MR{$note["id"]}');"><span class="UIButtonTxt">Reply</span></a>
                    </div>
            </div>  
MARKUP;
    }
    
}
?>
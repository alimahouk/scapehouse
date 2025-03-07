<?php
class Logged_Model_NoteReply {
    
    static function display($noteReply,$noteOwnerid=NULL){
        
        switch ($noteReply["color"]) {
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
         
        $fullname = $noteReply["firstname"]. " " .$noteReply["lastname"];
        $noteReply["exactTime"] = Model_StaticFunctions::formatDateFromTimestamp($noteReply["time"]);
        $noteReply["relativeTime"] = Model_StaticFunctions::relativeTime(strtotime($noteReply["time"]));
        $noteReply["content"] = make_clickable(nl2br(htmlspecialchars($noteReply["content"])));
        
        if($noteReply["userid"] == $GLOBALS["session"]->id || $noteOwnerid == $GLOBALS["session"]->id){
          $deleteBtn = '<span class="noteReplyActions"><a onclick="O.note.deleteNoteReply(\'NR{$noteReply["id"]}\');">Delete</a></span>';  // <---- ERROR 
        }
        
        return <<<MARKUP
                    <div class="noteReply $color" id="NR{$noteReply["id"]}">
                            <a href="/{$noteReply["username"]}/profile"><img class="thmbnl" src="{$photo->display($noteReply["userid"],"user","small")}" alt="Abdullah Khan" title="Abdullah Khan" /></a>                
                            <div class="noteReplyBody">
                                    <a href="/{$noteReply["username"]}/profile" class="userName">{$fullname}</a>
                                    <p>{$noteReply["content"]}</p>
                                    <div class="noteReplyInfo">
                                            <span class="noteReplyTimestamp">4 hours ago</span>$deleteBtn
                                    </div>
                            </div>
                    </div>
MARKUP;
    }
    
}
?>
<?php
class Logged_Model_Watchlist {

    private $output;
    public  $watchers;

    function display($watch) {
        $photo = new Logged_Model_Photo();
        $session = Model_StaticFunctions::sessionContent();

        if (isset($this->watchers)) {
            $this->targetOrWatcherid = $watch['userid'];
        } else {
            $this->targetOrWatcherid = $watch['targetid'];
        }

       $this->processEntryActions($watch);
       $output = $this->output;
       
//This markup will always appear.
        return <<<MARKUP
<div class="watchlistEntry" id="W{$watch['id']}">
        <div class="watchlistBody">
            <div class="userPicture" style="margin-right: 7px;">
            <a href="/{$watch['username']}/profile"><img src="{$photo->display($this->targetOrWatcherid, "user", "small")}" alt="{$watch['fullname']}" style="width:35px;height:35px;"/></a></div>
            <div class="entryDetails">
                <h3 class="userName" style="margin-bottom: 0px;"><a href="/{$watch['username']}/profile">{$watch['fullname']}</a></h3>
                <div class="username">{$watch['username']}</div>
            </div>
        </div>
        {$output["entryActions"]}
      </div>
MARKUP;

//        if(!$watch && $this->watchers) {
//            echo "<div class=\"noWatchers\">
//		<h3>Nobody's watching you yet.</h3>
//		</div>";
//        } elseif (!$watch && !$this->watchers) {
//            echo <<<MARKUP
//    <div class="noWatching">
//            <h3>You're not watching anyone.</h3>
//            <div class="search">
//                    <form method="post">
//                    <h3>Find people by name or email</h3>
//                    <input type="text" class="UITextInput searchBox" placeholder="Search" name="searchBox" title="Search" />
//                    <a href="transition_1_with_results.html" class="UIButton search"><span>Search</span></a>
//                    </form>
//            </div>
//    </div>
//MARKUP;
//        }

    }
    function processEntryActions($watch) {
        $session = Model_StaticFunctions::sessionContent();
        // If this is not a call for displaying the watchers then.. display the people the user is watching
        if (!$this->watchers) {
            // This markup only appears if the owner of the watchlist is the session user.

            if($watch["userid"] == $session->id) {

                $this->output["entryActions"] = <<<MARKUP
                <div class="watchStatusHolder">
                <a class="UIButton grey watch" title="Stop watching this person" onclick="O.watch.unwatch(this,{$watch["id"]})"><span class="UIIcon px16 cross"></span><span class="UIButtonTxt">Unwatch</span></a>
                </div>
<div class="entryActions hidden">
        <a class="dropDownMenuCandidate options" onclick="$('#W{$watch['id']} .UIMenu').show();event.cancelBubble = true;event.stopPropagation();">Options</a>
        <div class="UIMenu hidden" style="position:absolute; z-index:3;">
         <ul>
          <li class="UIMenuItem"><a>Unwatch</a></li>
          <li class="UIMenuItem"><a>Block this user</a></li>
          <li class="UIMenuItem"><a>Report</a></li>
          
         </ul>
        </div>
       </div>

MARKUP;
            }else {

                // If not then this block checks to see if the target of the watch is the session user or not. and only displays this
                // markup if false.

                if($this->targetOrWatcherid != $session->id) {

                    $watchlistTable = new Logged_Model_DbTable_Watchlist();
                    // if the watchlist owner is watching the target then it displays the following markup.
                    if($watchlistTable->getUserWatch($this->targetOrWatcherid)) {
                        $this->output["entryActions"] = <<<MARKUP
                        
            <div class="watchStatusHolder">
               <div class="watchStatus"><span class="UIIcon px16 tick"></span>Watching</div>
            </div>                       

MARKUP;
                    } else {
                        // else it displays this markup with the watch button.
                        $this->output["entryActions"] = <<<MARKUP
            <div class="watchStatusHolder">
             <a class="UIButton large grey watch" title="Add this person to your watchlist" onclick="O.watch.create(this,{$this->targetOrWatcherid})"><span class="UIIcon px16 watchlist"></span><span class="UIButtonTxt">Watch</span></a>
            </div>      
MARKUP;
                    }
                }else {
                    // If the watch target is the session user then this markup is displayed.
                    $this->output["entryActions"] = <<<MARKUP
            <div class="watchStatusHolder">
               <div class="currentUserIndicator">That's you!</div>
            </div>                          
MARKUP;
                }

            }
        }
        // If the this IS a request for the watchers then this markup is displayed.
        elseif($this->watchers) {

            // If not then this block checks to see if the target of the watch is the session user or not. and only displays this
            // markup if false.
            if($this->targetOrWatcherid != $session->id) {

                $watchlistTable = new Logged_Model_DbTable_Watchlist();
                // if the watchlist owner is watching the target then it displays the following markup.
                if($watchlistTable->getUserWatch($this->targetOrWatcherid)) {
                    $this->output["entryActions"] = <<<MARKUP
            <div class="watchStatusHolder">
               <div class="watchStatus"><span class="UIIcon px16 tick"></span>Watching</div>
            </div> 
MARKUP;
                } else {

                    $this->output["entryActions"] = <<<MARKUP
            <div class="watchStatusHolder">
             <a class="UIButton large grey watch" title="Add this person to your watchlist" onclick="O.watch.create(this,{$this->targetOrWatcherid})"><span class="UIIcon px16 watchlist"></span><span class="UIButtonTxt">Watch</span></a>
            </div>      
MARKUP;
                }
            } else {
                // If the watch target is the session user then this markup is displayed.
                $this->output["entryActions"] = <<<MARKUP
            <div class="watchStatusHolder">
               <div class="currentUserIndicator">That's you!</div>
            </div>    
MARKUP;
            }
        }

 //       echo "</div>";

    }
}
?>

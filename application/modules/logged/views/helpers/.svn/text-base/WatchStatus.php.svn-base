<?php
    class Logged_View_Helper_WatchStatus extends Zend_View_Helper_Abstract
    {
        public function watchStatus($userid)
        {
            if(!$GLOBALS["session"]){ // if public, return false
                return false;
            }
            
            $watchlistTable = new Logged_Model_DbTable_Watchlist();
            $isWatching = $watchlistTable->isWatching($GLOBALS["session"]->id,$userid);
            
            if($userid == $GLOBALS["session"]->id){
                if(!strstr($_SERVER["REQUEST_URI"],"profile")){
                return "<div class='currentUserIndicator'>That's you!</div>";
                }
            }elseif($isWatching){
                return "<div class='watchStatus'><span class='UIIcon px16 tick'></span>Watching</div>";
            }else{
                return "<a class='UIButton large grey watch' title='Add this person to your watchlist' onclick='O.watch.create(this,$userid);' ><span class='UIIcon px16 watchlist'></span><span class='UIButtonTxt'>Watch</span></a>";  
            }
        
        }
    }
?>
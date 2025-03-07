<?php
class Logged_Model_Privacy {

    static function isAllowed($userid, $viewerid, $subject, $scapeid = NULL) {

        $session = Model_StaticFunctions::sessionContent();
        if($userid == $session->id) {
            // always allow to owner of the subject
            return true;
        }

        
        if($scapeid) {
            // Scape specfic privacy
            $scapePrivacyCustomTable = new Logged_Model_DbTable_ScapePrivacyCustom();
            $privacy = $scapePrivacyCustomTable->getPrivacy($userid, $scapeid);

            $show = json_decode($privacy["show"]);
            $hide = json_decode($privacy["hide"]);

            switch (true) {
                case !$show && !$hide:
                    return true;
                    break;
                case $show:
                    if(array_search($viewerid,$show) !== FALSE) {
                        return true;
                    }else {
                        return false;
                    }
                    break;
                case $hide:
                    if(array_search($viewerid,$hide) !== FALSE) {
                        return false;
                    }else {
                        return true;
                    }
                    break;
                default:
                    return true;
                    break;
            }
        }



        $privacyGlobalTable = new Logged_Model_DbTable_PrivacyGlobal();
        $privacyGlobalTable = $privacyGlobalTable->getPrivacy($userid);

        switch ($privacy[$subject]) {
            case 0:
            // Not allowed, only user.
                return false;
                break;
            case 1;
            // Allowed to every one
                return true;
                break;
            case 2:

            // Global custom privacy settings apply --- uses strict !== FALSE operator because 1st index returns as 0;

                $privacyCustomTable = new Logged_Model_DbTable_PrivacyCustom();
                $privacy = $privacyCustomTable->hasPrivacy($userid, $subject, TRUE);

                $show = json_decode($privacy["show"]);
                $hide = json_decode($privacy["hide"]);

                switch (true) {
                    case !$show && !$hide:
                        return true;
                        break;
                    case $show:
                        if(array_search($viewerid,$show) !== FALSE) {
                            return true;
                        }else {
                            return false;
                        }
                        break;
                    case $hide:
                        if(array_search($viewerid,$hide) !== FALSE) {
                            return false;
                        }else {
                            return true;
                        }
                        break;
                    default:
                        return true;
                        break;
                }

            case 3:
            // Watchers only
                $watchlistTable = new Logged_Model_DbTable_Watchlist();
                if($watchlistTable->isWatching($viewerid, $userid)) {
                    return true;
                }else {
                    return false;
                }
                break;


            default:
            // No condition matched, ERROR~!

                echo "There is an error in the privacy conditions";
                return false;
                break;
        }
    }

}
?>

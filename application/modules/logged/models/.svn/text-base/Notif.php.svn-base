<?php

class Logged_Model_Notif {

    public function process() {

        $notifTable = new Logged_Model_DbTable_Notif();

        $notifs = $notifTable->getNotifs();

        foreach ($notifs as $notif) {

            switch (true) {

                case ($notif["action"] == "like"):

                    switch (true) {
                        case($notif["subject"] == "scape"):
                            $scapeLike[$notif["subjectid"]][] = $notif;                          
                            break;
                        case($notif["subject"] == "reply"):
                            $replyLike[$notif["subjectid"]][] = $notif;
                            break;
                        case($notif["subject"] == "photo"):
                            $photoLike[$notif["subjectid"]][] = $notif;
                            break;
                    }
                    break;
                case ($notif["action"] == "dislike"):

                    switch (true) {
                        case($notif["subject"] == "scape"):
                            $scapeDislike[$notif["subjectid"]][] = $notif;
                            break;
                        case($notif["subject"] == "reply"):
                            $replyDislike[$notif["subjectid"]][] = $notif;
                            break;
                        case($notif["subject"] == "photo"):
                            $photoDislike[$notif["subjectid"]][] = $notif;
                            break;
                    }
                    break;
                case ($notif["action"] == "watch"):

                    switch (true) {
                        case($notif["subject"] == "user"):
                            $userWatch[$notif["subjectid"]][] = $notif;
                            break;
                    }
                    break;
                case ($notif["action"] == "reply"):

                    switch (true) {
                        case($notif["subject"] == "scape"):
                            $scapeReply[$notif["subjectid"]][] = $notif;
                            break;
                    }
                    break;
                case ($notif["action"] == "comment"):

                    switch (true) {
                        case($notif["subject"] == "reply"):
                            $replyComment[$notif["subjectid"]][] = $notif;
                            break;
                        case($notif["subject"] == "photo"):
                            $photoComment[$notif["subjectid"]][] = $notif;
                            break;
                    }
                    break;
            }
        }
        
        $notifArray = array(
            "scapeLike" => $scapeLike,
            "replyLike" => $replyLike,
            "photoLike" => $photoLike,
            "scapeDislike" => $scapeDislike,
            "replyDislike" => $scapeDislike,
            "photoDislike" => $scapeDislike,
            "userWatch" => $userWatch,
            "scapeReply" => $scapeReply,
            "replyComment" => $replyComment,
            "photoComment" => $photoComment
        );

        foreach($notifArray as $label => $arrays){
           foreach((array)$arrays as $key => $value){
               $notifArray[$label][$key]["count"] = count($value);
           }
        }

        return $notifArray;
    }

}
?>

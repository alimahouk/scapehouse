<?php

class Logged_AjaxController extends Zend_Controller_Action {

    public function init() {
  
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext('displayphoto', 'xml')
                //->addActionContext('editscape', 'xml')
                ->initContext();
        if(!$this->_request->isXmlHttpRequest()) {
            if($this->_request->getActionName() != "uploadphoto") {
                $this->_redirect('/index');
            }
        }
        $this->_helper->layout->disableLayout();

    }

    public function indexAction() {
        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

    }
// SCAPE ACTIONS --------------

    /* createscapeAction ():
     * When hit by an ajax call with proper scape data . Creates a new scape .
    */
    public function createscapeAction () {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $scape = $_POST["scape"];

        $title = $_POST["title"];
        
        $private = $_POST["private"];
        
        if(!empty($_POST["tags"])){
            $tags = explode(",",$_POST["tags"]);
        }else{
            $private = "false";
        }
        
        // $title = htmlentities(str_replace("+", "%2B", $_POST["title"]));

        if ($scape != "" && $scape != "<br>" && $scape != "undefined" || $title != "") {

            $scapeTable = new Logged_Model_DbTable_Scape();

            $scape = Logged_Model_Publisher::sanatizePost($scape,"scape");

            $title = mb_substr(trim($title),0,100,"UTF8");

            $scapeTable->createScape($scape,$title,$tags,$private);
            
            echo json_encode($GLOBALS["jsonResponce"]);
        }
    }

    public function editscapeAction () {
        
        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---
        
        $scape = $_POST["scape"];
        $scapeid = $_POST["scapeid"];
        $title = $_POST["title"];

        if (! empty($scape) && $scape != "<br>" && $scape != "undefined" || $title != "" ) {

            $scapeTable = new Logged_Model_DbTable_Scape();

            $scape = Logged_Model_Publisher::sanatizePost($scape,"scape");
            $title = mb_substr(trim($title),0,100,"UTF8");

            $scapeTable->editScape($scape,$title,$scapeid);
        }

    }

    /* displaytimelineAction ():
     * When hit by an ajax call displays the timeline on the homepage or the profile page depending on the request..
    */

    public function displaytimelineAction () {



        $location = $_POST['location'];
        $userid = $_POST['userid'];
        $loadMore = $_POST['loadMore'];
        $scapeTable = new Logged_Model_DbTable_Scape();

        switch(true) {
            case($location == "home"):
                $this->view->location = $location;
                $this->view->scapes = $scapeTable->getLatestScapes($loadMore);
                break;
            case($location == "profile" && $userid):
                $this->view->location = $location;
                $this->view->scapes = $scapeTable->getUserScapes($userid,$loadMore);
                break;
        }

    }

    /* updatetimelineAction ():
     * When hit by an ajax call updates the timeline on the homepage with the latest scape..
    */

    public function updatetimelineAction () {

        $scapeid = $_POST['scapeid'];


        $scapeTable = new Logged_Model_DbTable_Scape();
        $this->view->scape = $scapeTable->updateScape($scapeid);
    }

// SCAPE ACTIONS END--------------
//
// REPLY ACTIONS --------------

    /* displayrepliesAction():
     * When hit by an ajax call displays all the replies on the specified scape..
    */

    public function displayrepliesAction() {



        $scapeid = $_POST["scapeid"];
        $limit = $_POST["limit"];
        $offset = $_POST["offset"];


        $replyTable = new Logged_Model_DbTable_Reply();
        $this->view->replies = $replyTable->getRepliesOnScape($scapeid, $limit, $offset);
        $this->view->scapeid = $scapeid;

    }

    /* createreplyAction ():
     * When hit by an ajax call with proper reply data and scapeid .
     * Creates a new reply under the target scape.
    */

    public function createreplyAction () {    

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $reply = $_POST["reply"];
        $scapeid = $_POST["scapeid"];

        if (! empty($reply) && $scape != "<br>" && $reply != "undefined") {

            $replyTable = new Logged_Model_DbTable_Reply();

            $reply = Logged_Model_Publisher::sanatizePost($reply,"reply");
            
           $replyTable->createReply($reply,$scapeid);
           
           echo json_encode($GLOBALS["jsonResponce"]);
        }

    }

    /* updatereplyAction():
     * When hit by an ajax call with proper a scapeid .
     * displays the latest reply on the scape.
    */

    public function postedreplyAction() {



        $replyid = $_POST["replyid"];

        $replyTable = new Logged_Model_DbTable_Reply();
        $this->view->reply = $replyTable->postedReply($replyid);
    }

    public function editreplyAction() {



        $reply = $_POST['reply'];
        $replyid = $_POST["replyid"];

        if (! empty($reply) && $reply != "<br>" && $reply != "undefined") {

            $replyTable = new Logged_Model_DbTable_Reply();

            $reply = Logged_Model_Publisher::sanatizePost($reply,"reply");

            $this->view->editedReply = $replyTable->editReply($reply,$replyid);
        }

    }
// REPLY ACTIONS END--------------

// COMMENT ACTIONS --------------

    /* createcommentAction():
     * When hit by an ajax call with proper a element id and id type .
     * creates a comment on the specified element
    */

    public function createcommentAction() {
        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $comment = $_POST["comment"];
        $elmid = $_POST["elmid"];
        $idType = $_POST["idType"];

        if (! empty($comment) && $comment != "<br>" && $comment != "undefined") {

            $commentTable = new Logged_Model_DbTable_Comment();

            $comment = Logged_Model_Publisher::sanatizePost($comment,"comment");

            $commentTable->createComment($comment, $elmid, $idType);
            
            echo json_encode($GLOBALS["jsonResponce"]);            
        }

    }

    /* displaycommentsAction():
     * When hit by an ajax call with proper a element id and id type .
     * displays all the comments on the specified element
    */

    public function displaycommentsAction() {



        $elmid = $_POST["elmid"];
        $idType = $_POST["idType"];

        $commentTable = new Logged_Model_DbTable_Comment();
        $this->view->comments = $commentTable->getAllCommentsOnElm($elmid,$idType);
        $this->view->elmid = $elmid ;
        $this->view->idType = $idType;
    }

    /* latestcommentAction():
     * Gets the latest comment on the element specified
    */
    public function postedcommentAction() {



        $commentid = $_POST["commentid"];

        $commentTable = new Logged_Model_DbTable_Comment();
        $this->view->comment = $commentTable->postedComment($commentid);

    }

    public function editcommentAction() {




        $comment = $_POST['comment'];
        $commentid = $_POST["commentid"];

        if (! empty($comment) && $comment != "<br>" && $comment != "undefined") {

            $commentTable = new Logged_Model_DbTable_Comment();

            $comment = Logged_Model_Publisher::sanatizePost($comment,"comment");

            $this->view->editedComment = $commentTable->editComment($comment,$commentid);
        }

    }
// COMMENT ACTIONS END --------------

// LIKES AND DISLIKES ACTIONS --------------
    public function likesdislikesAction() {



        $d = $_POST['d'];
        $l = $_POST['l'];

        $elmid = $_POST["elmid"];
        $idType = $_POST["idType"];

        $dislikeTable = new Logged_Model_DbTable_Dislike();
        $likeTable = new Logged_Model_DbTable_Like();

        if (isset($l)) {



            $likeTable->like($elmid,$idType);
            $dislikeTable->dislikeOffOnLike($elmid,$idType);

            $this->view->elmid = $elmid;
            $this->view->idType = $idType;

        } elseif (isset($d)) {



            $dislikeTable->dislike($elmid,$idType);
            $likeTable->likeOffOnDislike($elmid,$idType);

            $this->view->elmid = $elmid;
            $this->view->idType = $idType;
        }

    }

    public function likedislikelistAction() {



        $d = $_POST['d'];
        $l = $_POST['l'];

        $elmid = $_POST["elmid"];
        $idType = $_POST["idType"];

        if (isset($l) && $l != "null") {
            $likeTable = new Logged_Model_DbTable_Like();
            $this->view->likes  = $likeTable->getAllOULikes($elmid,$idType);

        } elseif (isset($d) && $d != "null") {

            $dislikeTable = new Logged_Model_DbTable_Dislike();
            $this->view->dislikes  = $dislikeTable->getAllOUDislikes($elmid,$idType);

        }


    }
    //---------------------


// LIKES AND DISLIKES ACTIONS END --------------


    /* deleteAction():
* Universal delete action for all deletable elements,
* When hit by an ajax call along with a proper element id. Deletes the specified element.
    */
    public function deleteAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $elmid = $_POST["elmid"];
        $idType = $_POST["idType"];
        $scapebox = $_POST["scapebox"];

        switch(true) {
            case($idType == "scape"):
                if($scapebox == "true"){
                 $scapeboxTable = new Logged_Model_DbTable_Scapebox();
                 $scapeboxTable->delete($GLOBALS["session"]->id,$elmid);
                }else{
                $scapeTable = new Logged_Model_DbTable_Scape();
                $scapeTable->deleteScape($elmid);   
                }


                break;

            case($idType == "reply"):

                $replyTable = new Logged_Model_DbTable_Reply();
                $replyTable->deleteReply($elmid);

                break;

            case($idType == "comment"):

                $commentTable = new Logged_Model_DbTable_Comment();
                $commentTable->deleteComment($elmid);

                break;

            case($idType == "photo"):

                $photoTable = new Logged_Model_DbTable_Photo();
                $photoTable->deletePhoto($elmid);

                break;
           case($idType == "note"):

                $noteTable = new Logged_Model_DbTable_Note();
                $noteTable->deleteNote($elmid);

            break;
        }
    }
// WATCHLIST ACTIONS --------------

    public function watchAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $targetid = $_POST['targetid'];
        $watchlistTable = new Logged_Model_DbTable_Watchlist();
        $watchlistTable->watch($targetid);
    }

    public function ignoreAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $watchid = $_POST["watchid"];
        $profile = $_POST["profile"];

        $watchlistTable = new Logged_Model_DbTable_Watchlist();
        $watchlistTable->ignore($watchid,$profile);

    }

    public function displaywatchlistAction() {



        $userid = $_POST['userid'];

        if (isset($_POST['watchers'])) {
            $watchlistTable = new Logged_Model_DbTable_Watchlist();
            $this->view->watch = $watchlistTable->getAllOtherWatch($userid);
            $this->view->watchers = 1;
        } else {
            $watchlistTable = new Logged_Model_DbTable_Watchlist();
            $this->view->watch = $watchlistTable->getAllUserWatch($userid);
        }
    }

// WATCHLIST ACTIONS END--------------

// PHOTO ACTIONS --------------
    public function uploadphotoAction() {
        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $photoProcessor = new Logged_Model_Photo();
        echo $photoProcessor->save($_FILES['imageFile']);

    }

    function makecurrentAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $photoid = $_POST['photoid'];

        $photoTable = new Logged_Model_DbTable_Photo();
        $photoTable->makeCurrent($photoid);

    }

    function displayphotoAction() {

        $photoid = $_POST['photoid'];
        $this->view->photoid = $photoid;

        $photoTable = new Logged_Model_DbTable_Photo();
        $this->view->photo = $photoTable->getPhotos($photoid,'photo');

    }

    function editphotodetailsAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $photoid = $_POST['photoid'];
        $caption = $_POST['caption'];
        $location = $_POST['location'];

        if(isset($photoid,$caption,$location)) {

            $photoTable = new Logged_Model_DbTable_Photo();
            
              $caption = mb_substr(trim($caption),0,500,"UTF8");
              $location = mb_substr(trim($location),0,50,"UTF8");
            
            //$caption = Logged_Model_Publisher::sanatizePost($caption,"text");
           //$location = Logged_Model_Publisher::sanatizePost($location,"text");

            $photoTable->editDetails($photoid,$caption,$location);

        }

    }

// PHOTO ACTIONS END--------------

// NOTIFS ACTIONS --------------

    function deletenotifAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $notifTable = new Logged_Model_DbTable_Notif();
        $notifTable->deleteNotifs($_POST["jsonids"]);

    }

// PROFILE ACTIONS --------------

    function savepmAction() {

        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $pm = mb_substr($_POST['pm'],0,400,"UTF8"); 

        $profileTable = new Logged_Model_DbTable_Profile();
        $profileTable->save($GLOBALS["session"]->id,$pm,23);
        
        echo $pm;

    }

    function infodisplayAction() {
        
        if($_POST["ownerid"] == $GLOBALS["session"]->id) {
            $isPublic = false;
            $isOwner = true;
        } elseif($GLOBALS["session"]) {
            $isPublic = false;
            $isOwner = false;
        } else {
            $isPublic = true;
            $isOwner = false;
        }
        
        if(!$isPublic){
        $profileTable = new Logged_Model_DbTable_Profile();
        $profileInfo = $profileTable->getProfileInfo($_POST["ownerid"]);
        
        foreach($profileInfo as $profileInfo){
            
            if($decoded = json_decode($profileInfo["data"])){
                
                if(is_object($decoded)){
                 $profileInfo["data"] = (array) $decoded;
                }else{
                 $profileInfo["data"] = $decoded;    
                }
                
            }

            $info[$profileInfo["name"]] = $profileInfo["data"];
        }
        
    }
        
        $this->view->info = $info;
        
        // Push profile owner info into view.
        $this->view->assign($_POST);
        
        $this->view->isPublic = $isPublic;
        $this->view->isOwner = $isOwner;

    }
    
    function noteboardAction(){
        
       $reciverid = $_POST["ownerid"];
       $noteTable = new Logged_Model_DbTable_Note();

       $this->view->notes = $noteTable->getNotesByReciverid($reciverid,0);
       $this->view->noteCount = count($noteTable->getAllNoteIdsByReciverid($reciverid));
    }
    
    function createnoteAction(){
        
        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---
        
        $content = mb_substr(trim($_POST["content"]),0,300,"UTF8");
        $reciverid = $_POST["reciverid"];
        $color = $_POST["color"];
        
        if(array_search($color,array(1,2,3,4)) === FALSE){ // in case there is a mess with the colors, set to default.
            $color = 1;
        }
        
        $noteTable = new Logged_Model_DbTable_Note();
        $noteTable->createNote($content,$GLOBALS["session"]->id,$reciverid,$color);
    }
    
    function getnoteAction(){
        
        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---
        
        $id = $_POST["id"];
        
        $noteTable = new Logged_Model_DbTable_Note();
        $note = $noteTable->getNoteById($id);
        echo Logged_Model_Note::display($note);

    }
    
    function getnotesbyreciveridAction(){
        
        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---
        
        $reciverid = $_POST["userid"];
        $loadMore = $_POST["loadMore"];
        
        $noteTable = new Logged_Model_DbTable_Note();
        $notes = $noteTable->getNotesByReciverid($reciverid,$loadMore);
        
        foreach($notes as $note){
            echo Logged_Model_Note::display($note);
        }
        
    }
    
    function createnotereplyAction(){
        
        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---
        
        $content = mb_substr(trim($_POST["content"]),0,300,"UTF8");
        $noteid = $_POST["noteid"];
        
        $noteReplyTable = new Logged_Model_DbTable_NoteReply();
        $noteReplyTable->createNoteReply($content,$GLOBALS["session"]->id,$noteid);
    }
    
    function getnotereplyAction(){
        
        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $id = $_POST["id"];
        
        $noteReplyTable = new Logged_Model_DbTable_NoteReply();
        $noteReplyProcessor = new Logged_Model_NoteReply();
        
        echo $noteReplyProcessor->display($noteReplyTable->getNoteReplyById($id));
    }

// SIGNUP ACTIONS ----------------

    function checkusernameAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $userTable = new Model_DbTable_Users();
        if ($userTable->getUserByUsername(trim($_POST["username"]))) {
            echo "true";
        }
    }

    function checkemailAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $userTable = new Model_DbTable_Users();
        if ($userTable->checkUniqueEmail(trim($_POST["email"]))) {
            echo "true";
        }
    }

    function checkcaptchaAction() {

        // No view action---

        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        // require_once('/recaptcha/recaptchalib.php');

        $publickey = "6LcMtLkSAAAAAKh6tA85667nh0B8CF9NJ63KLVxE";
        $privatekey = "6LcMtLkSAAAAAAuB_HpkGyZBZnpHDlboQ9pbK33X";

        $recaptcha = new Zend_Service_ReCaptcha($publickey, $privatekey);

        $result = $recaptcha->verify(
                $_POST['recaptcha_challenge_field'],
                $_POST['recaptcha_response_field']
        );

        if ($result->isValid()) {
            echo "success";
        }else {
            echo "failure";
        }


    }
// SEARCH ------

    function searchAction() {

        $query = $_POST["query"];

        $usersTable = new Model_DbTable_Users();
        $validEmail = new Zend_Validate_EmailAddress();

        if($query[0] == "@") {
            $query = substr($query,1);
            $session = Model_StaticFunctions::sessionContent();
            if ($session->username == $query){
                die();
            }
            $this->view->hits =  $usersTable->getUserByUsername($query,TRUE);
        }elseif($validEmail->isValid($query)) {
            $this->view->hits =  $usersTable->getUserByEmail($query);
        }else {
            $this->view->hits =  $usersTable->searchUsersByName($query,4,0,TRUE);
        }

    }

    function watchlistsearchAction() {

        $query = $_POST["query"];
        $userid = $_POST["userid"];
        $type = $_POST["type"];

//        $usersTable = new Model_DbTable_Users();
        $validEmail = new Zend_Validate_EmailAddress();
        $watchListTable = new Logged_Model_DbTable_Watchlist();

        if($type == "watchers") {
            $this->view->watches = $watchListTable->searchWatchers($query, $userid);
        }elseif($type == "watching") {
            $this->view->watches = $watchListTable->searchWatching($query, $userid);
        }

        $this->view->type = $type;
        $this->view->userid = $userid;

    }
// PRIVACY -----
    function privacysaveAction() {

        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        $subject = $_POST["id"];
        $show = (!empty($_POST["show"]))?explode("|", $_POST["show"]):NULL;
        $hide = (!empty($_POST["hide"]))?explode("|", $_POST["hide"]):NULL;

        $privacy = new Logged_Model_DbTable_PrivacyGlobal();
        $privacy = $privacy->getPrivacy($userid);

        if($privacy["maxPrivacy"] != 1) {
            unset($hide);
        }

        $privacyCustomTable = new Logged_Model_DbTable_PrivacyCustom();
        $privacyCustomTable->save($subject, $show, $hide);

    }

    function showprivacyAction() {



        $subject = $_POST["id"];

        $privacyCustomTable = new Logged_Model_DbTable_PrivacyCustom();
        $this->view->privacy = $privacyCustomTable->getPrivacy($subject);

    }

// UI WINDOWS --------
    function uiwlistusersAction() {


        $this->view->userids = $_POST["jsonids"];
        $this->view->type = $_POST["type"];
    }

    function uiwreplyAction() {


        $this->view->replyid = $_POST["replyid"];

        $replyTable = new Logged_Model_DbTable_Reply();
        $this->view->reply = $replyTable->postedReply($_POST["replyid"]);
    }

    function uiwphotoAction() {


        $this->view->photoid = $_POST["photoid"];

        $photoTable = new Logged_Model_DbTable_Photo();
        $this->view->photo = $photoTable->getPhotoDetails($_POST["photoid"]);
    }
    
    function uiwcommentsAction(){
        $this->view->id = $_POST["id"];
        $this->view->idType = $_POST["idType"];
    }

    function uiwfeedbackAction() {
        // Display feedback window
    }


// FEEDBACK -------

    function submitfeedbackAction() {

        $type = $_POST["type"];
        $content = $_POST["content"];
        $thumbsup = $_POST["thumbsup"];
        $thumbsdown = $_POST["thumbsdown"];

        $feedbackTable = new Logged_Model_DbTable_Feedback();
        $feedbackTable->save($type, $content, $thumbsup, $thumbsdown);

    }
//NOTIFICATIONS

    function updatebadgeAction(){
        
       // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---
        
        $notifTable = new Logged_Model_DbTable_Notif();
        echo $notifTable->countNotifs();
        
    }
//NOTIFICATION MAILING

    function notifmailerAction(){
        
        // No view action---
        $this->_helper->viewRenderer->setNoRender(true);
        // ---
    
     Logged_Model_NotifMailer::send($_GET["userid"],$_GET["reciverid"],$_GET["actionid"],$_GET["subjid"],$_GET["relType"]);
    }

}


?>

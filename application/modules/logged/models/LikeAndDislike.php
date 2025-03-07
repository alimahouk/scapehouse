<?php

class Logged_Model_LikeAndDislike {
// Var declaration --------

    public $Lyou;
    public $LyouAndOther ;
    public $LyouAndNumOther;
    public $Lother;
    public $LnumOther;
    public $Dyou;
    public $DyouAndOther ;
    public $DyouAndNumOther;
    public $Dother;
    public $DnumOther;
    public $likeDislike; // markup container

// Var declaration end--------


    function displayLikeAndDislike ($elmid, $idType) {

        $session = Model_StaticFunctions::sessionContent();
        $sessionUsername = $session->username;

        $processLike = new Logged_Model_DbTable_Like();
        $this->allLikes = $processLike->getAllLikes($elmid,$idType);


        $processDislike = new Logged_Model_DbTable_Dislike();
        $this->allDislikes = $processDislike->getAllDislikes($elmid,$idType);

        $likeUsername = json_decode($this->allLikes["username"]);
        $likeUserid = $this->allLikes["userid"];

        $dislikeUsername = json_decode($this->allDislikes["username"]);
        $dislikeUserid = $this->allDislikes["userid"];

        $likeCount = count($likeUsername);
        $dislikeCount = count($dislikeUsername);


        // -------------------------------------LIKES PROCESSOR------------------------------------
        if ($this->allLikes) {
            switch (true) {
                case ($likeCount == 1 && array_search($sessionUsername, $likeUsername) !== FALSE):

                    $this->Lyou = 1;
                    $this->LyouAndOther = NULL;
                    $this->LyouAndNumOther = NULL;
                    $this->Lother = NULL;
                    $this->LnumOther = NULL;

                    $like = "You like this.";
                    break;

                case ($likeCount == 2 &&  array_search($sessionUsername, $likeUsername) !== FALSE):

                    $this->Lyou = NULL;
                    $this->LyouAndOther = 1;
                    $this->LyouAndNumOther = NULL;
                    $this->Lother = NULL;
                    $this->LnumOther = NULL;

                    $userName = json_decode($this->allLikes["fullname"]);
                    $key = array_search($sessionUsername, $likeUsername);

                    if($key === 0) {
                        $key = 1;
                    }elseif($key === 1) {
                        $key = 0;
                    }

                    $like = "You and <a href='/$likeUsername[$key]/profile'>{$userName[$key]}</a> like this";
                    break;

                case ($likeCount > 2 && array_search($sessionUsername, $likeUsername) !== FALSE):

                    $this->Lyou = NULL;
                    $this->LyouAndOther = NULL;
                    $this->LyouAndNumOther = 1;
                    $this->Lother = NULL;
                    $this->LnumOther = NULL;

                    $likeCount--;
                    $like = "You and <a onclick='O.UIWindow.listUsers(\"$likeUserid\",\"like\");'>{$likeCount} people</a> like this";
                    break;

                case ($likeCount == 1 && array_search($sessionUsername, $likeUsername) === FALSE):

                    $this->Lyou = NULL;
                    $this->LyouAndOther = NULL;
                    $this->LyouAndNumOther = NULL;
                    $this->Lother = 1;
                    $this->LnumOther = NULL;

                    $userName = json_decode($this->allLikes["fullname"]);
                    $key = array_search($sessionUsername, $likeUsername);

                    if($key === 0) {
                        $key = 1;
                    }elseif($key === 1) {
                        $key = 0;
                    }

                    $like = "<a href='/$likeUsername[$key]/profile'>{$userName[$key]}</a> likes this";
                    break;

                case ($likeCount >= 2 && array_search($sessionUsername, $likeUsername) === FALSE):

                    $this->Lyou = NULL;
                    $this->LyouAndOther = NULL;
                    $this->LyouAndNumOther = NULL;
                    $this->Lother = NULL;
                    $this->LnumOther = 1;

                    $like = "<a onclick='O.UIWindow.listUsers(\"$likeUserid\",\"like\");'>{$likeCount} people</a> like this";
                    break;

            }
        } else {
            $this->Lyou = NULL;
            $this->LyouAndOther = NULL;
            $this->LyouAndNumOther = NULL;
            $this->Lother = NULL;
            $this->LnumOther = NULL;
        }

        // -------------------------------------DISLIKE PROCESSOR------------------------------------


        if ($this->allDislikes) {
            switch (true) {
                case ($dislikeCount == 1 && array_search($sessionUsername, $dislikeUsername) !== FALSE):

                    $this->Dyou = 1;
                    $this->DyouAndOther = NULL;
                    $this->DyouAndNumOther = NULL;
                    $this->Dother = NULL;
                    $this->DnumOther = NULL;

                    $dislike = "You don't like this.";
                    break;

                case ($dislikeCount == 2 && array_search($sessionUsername, $dislikeUsername) !== FALSE):

                    $this->Dyou = NULL;
                    $this->DyouAndOther = 1;
                    $this->DyouAndNumOther = NULL;
                    $this->Dother = NULL;
                    $this->DnumOther = NULL;

                    $userName = json_decode($this->allDislikes["fullname"]);
                    $key = array_search($sessionUsername, $dislikeUsername);

                    if($key === 0) {
                        $key = 1;
                    }elseif($key === 1) {
                        $key = 0;
                    }

                    $dislike = "You and <a href='/$dislikeUsername[$key]/profile'>{$userName[$key]}</a> don't like this";

                    break;

                case ($dislikeCount > 2 && array_search($sessionUsername, $dislikeUsername) !== FALSE):

                    $this->Dyou = NULL;
                    $this->DyouAndOther = NULL;
                    $this->DyouAndNumOther = 1;
                    $this->Dother = NULL;
                    $this->DnumOther = NULL;

                    $dislikeCount--;
                    $dislike = "You and <a onclick='O.UIWindow.listUsers(\"$dislikeUserid\",\"dislike\");'>{$dislikeCount} people</a> don't like this";

                    break;

                case ($dislikeCount == 1 && array_search($sessionUsername, $dislikeUsername) === FALSE):

                    $this->Dyou = NULL;
                    $this->DyouAndOther = NULL;
                    $this->DyouAndNumOther = NULL;
                    $this->Dother = 1;
                    $this->DnumOther = NULL;

                    $userName = json_decode($this->allDislikes["fullname"]);
                    $key = array_search($sessionUsername, $dislikeUsername);

                    if($key === 0) {
                        $key = 1;
                    }elseif($key === 1) {
                        $key = 0;
                    }

                    $dislike = "<a href='/$dislikeUsername[$key]/profile'>{$userName[$key]}</a> dosen't like this";

                    break;

                case ($dislikeCount >= 2 && array_search($sessionUsername, $dislikeUsername) === FALSE):

                    $this->Dyou = NULL;
                    $this->DyouAndOther = NULL;
                    $this->DyouAndNumOther = NULL;
                    $this->Dother = NULL;
                    $this->DnumOther = 1;

                    $dislike = "<a onclick='O.UIWindow.listUsers(\"$dislikeUserid\",\"dislike\");'>{$dislikeCount} people</a> don't like this";

                    break;

            }
        } else {
            $this->Dyou = NULL;
            $this->DyouAndOther = NULL;
            $this->DyouAndNumOther = NULL;
            $this->Dother = NULL;
            $this->DnumOther = NULL;
        }

        if(!$like) {
            $likeHidden = "style='display:none;'";
        }elseif(!$dislike) {
            $dislikeHidden = "style='display:none;'";
        }
        if($like || $dislike) {
            $this->likeDislike = <<<MARKUP
                         <div class="userOpinions">

                          <div class="likes" $likeHidden><span class="UIIcon px16 like"></span>
                    $like
                         </div>

                         <div class="dislikes" $dislikeHidden><span class="UIIcon px16 dislike"></span>
                    $dislike
                          </div>

                         </div>

MARKUP;
        }

        return array('display'=>$this->likeDislike,
                'Lyou'=>$this->Lyou,
                'LyouAndOther'=>$this->LyouAndOther,
                'LyouAndNumOther'=>$this->LyouAndNumOther,
                'Dyou'=>$this->Dyou,
                'DyouAndOther'=>$this->DyouAndOther,
                'DyouAndNumOther'=>$this->DyouAndNumOther);


    }

}
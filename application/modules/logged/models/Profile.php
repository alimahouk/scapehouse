<?php

class Logged_Model_Profile {
    
    function __construct(){
       
    }
    
    public function save(){
        // Sanatize POST array
        
       //$_POST = $this->multiDimensionalarrayMap('htmlspecialchars',$_POST);
       $_POST = $this->multiDimensionalarrayTrim('trim',$_POST);
       
        switch($_POST["section"]){
             case "basic":
         // Basic section processor       
        $location = substr($_POST["location"],0,50);
        $sex = ($_POST["sex"] == "m" || $_POST["sex"] == "f" || $_POST["sex"] == "")?$_POST["sex"]:NULL;
        
        $nationality = $_POST["nationality"];
        
        if($nationality){
            if(count($nationality) <= 3){
        foreach($nationality as $key => $code){
            $countriesTable = new Logged_Model_DbTable_Countries();
            if(!$countriesTable->inRecord($code)){
              unset($nationality[$key]);
            }
          }
            }else{
                unset($nationality);
            }
        }
        
        // Reset out of order indices.
        $nationality = array_values($nationality);
        // Bday
        $bday = $_POST['bday'];
        $bmonth = $_POST['bmonth'];
        $byear = $_POST['byear'];
        
        
        
        if (!empty($bday) && !empty($bmonth) && !empty($byear)) {
            $birthdate = checkdate($bmonth, $bday, $byear)?"$byear-$bmonth-$bday":NULL;

            if($birthdate){
                $birthdayOption = $_POST["birthdayOption"];
                $birthday = array("date"=>$birthdate,"option"=>$birthdayOption);  
            }
            
        }
        
        $infoSet = array(
                      1 => $location,
                      2 => $sex,
                      3 => $nationality,
                      4 => $birthday
                      );
        
        break;
    
        case "personal":
        // Personal section processor
            $aboutme = substr($_POST["aboutme"],0,1000);
            $aboutme = $_POST["aboutme"];
            unset($_POST["aboutme"]);
            unset($_POST["section"]);
            
            foreach($_POST as $name => $detail){
                foreach($detail as $key => $unit){ // Pointless loop for the time being, can be used to sanatize.
                    $processedUnit[$key] = $unit;
                }
                $processedInfo[$name] = $processedUnit;
                
                if($processedInfo[$name]){
                $processedInfo[$name] = array_values($processedInfo[$name]);
                }
                
                unset($processedUnit);
            }
            
            $infoSet = array(
                      5 => $aboutme,
                      6 => $processedInfo["book"],
                      7 => $processedInfo["game"],
                      8 => $processedInfo["tvshow"],
                      9 => $processedInfo["movie"],
                      10 => $processedInfo["music"],
                      11 => $processedInfo["interest"],
                      12 => $processedInfo["need"]
                      );
            
            break;
        case "contact":
            // contact section processor
            
            unset($_POST["im"]["type"][0]);
            $im = array(
                        "text"=>array_values($_POST["im"]["text"]),
                        "type"=>array_values($_POST["im"]["type"])
                        );
            foreach($im["type"] as $key => $unit){
                if(!$im["text"][$key]){
                   unset($im["type"][$key]);
                }
            }
            
            if(empty($im["text"])){
                unset($im);
            }
            
            if (empty($_POST["website"]["url"])){
                unset($_POST["website"]);
            }

            $infoSet = array(
                      13 => array_values(array_unique($_POST["email"])),
                      14 => $im,
                      15 => $_POST["mobile"],
                      16 => $_POST["landphone"],
                      17 => $_POST["bb"],
                      18 => $_POST["website"]
                      );
            
            break;
        case "education":
            // education section processor
            
            if(!$_POST["school"]["text"]){
                unset($_POST["school"]);
            }
            
            if(!$_POST["collage"]["text"]){
                unset($_POST["collage"]);
            }
            
            if(count($_POST["major"]) > 3){
                unset($_POST["major"]);
            }
            
            $_POST["major"] = array_values($_POST["major"]);
            
            $infoSet = array(
                19 => $_POST["school"],
                20 => $_POST["collage"],
                21 => $_POST["major"]
            );
            
            break;
            case "work":
            // work section processor
            
            if(!$_POST["company"]["name"]){
                unset($_POST); 
            }
            
            $infoSet = array(22 => $_POST["company"]);
            break;
        }
        
        if($infoSet){
            
        $profileTable = new Logged_Model_DbTable_Profile();
        
        foreach($infoSet as $type => $info){
            
            if(empty($info) && $info != 0){
                $info = NULL;
            }
            
            if(is_array($info)){
                $info = json_encode($info);
            }
             $userid = $GLOBALS["session"]->id;
             $profileTable->save($userid,$info,$type);

        }

     }
    }

   /* public function process() {
        
        // Singeles
        $profileSingle["location"] = htmlentities($_POST['location']);
        $profileSingle["nickname"] = htmlentities($_POST['nickname']);
        $profileSingle["sex"] = htmlentities($_POST['sex']);
        // Bday
        $bday = htmlentities($_POST['bday']);
        $bmonth = htmlentities($_POST['bmonth']);
        $byear = htmlentities($_POST['byear']);

        $profileSingle["aboutme"] = htmlentities($_POST['aboutme']);
        $profileSingle["school"] = htmlentities($_POST['school']);
        $profileSingle["collage"] = htmlentities($_POST['collage']);
        $profileSingle["major"] = htmlentities($_POST['major']);
        $profileSingle["company"] = htmlentities($_POST['company']);
        $profileSingle["workposition"] = htmlentities($_POST['workposition']);
        // Work since
        $workmonth = htmlentities($_POST['workmonth']);
        $workyear = htmlentities($_POST['workyear']);

        $profileSingle["workabout"] = htmlentities($_POST['workabout']);

        if (!empty($bday) && !empty($bmonth) && !empty($byear)) {
            $profileSingle["birthday"] = checkdate($bmonth, $bday, $byear)?"$byear-$bmonth-$bday":NULL;

        }
        if (!empty($workmonth) && !empty($workyear)) {
            $profileSingle["worksince"] = checkdate($workmonth, 1, $workyear)?"$workyear-$workmonth-1":NULL;
        }

        //Multi(arrays)
        $profileInfoMultis["book"] = $_POST['book'];
        $profileInfoMultis["game"]  = $_POST['game'];
        $profileInfoMultis["show"]  = $_POST['show'];
        $profileInfoMultis["movie"]  = $_POST['movie'];
        $profileInfoMultis["interest"]  = $_POST['interest'];
        $profileInfoMultis["need"]  = $_POST['need'];
        $profileInfoMultis["email"]  = $_POST['email'];
        $profileInfoMultis['im'] = $_POST['im'];
        $profileInfoMultis['imtype'] = $_POST['imtype'];
        $profileInfoMultis["mobno"]  = $_POST['mobno'];
        $profileInfoMultis["fax"]  = $_POST['fax'];
        $profileInfoMultis["homeno"]  = $_POST['homeno'];
        $profileInfoMultis["officeno"]  = $_POST['officeno'];
        $profileInfoMultis["bb"]  = $_POST['bb'];
        $profileInfoMultis["website"] = $_POST['website'];

        $session = Model_StaticFunctions::sessionContent();
        $userid = $session->id;

        foreach ($profileInfoMultis as $type => $profileInfoMulti) {
            if($profileInfoMulti) {
                foreach ($profileInfoMulti as $key => $profileInfo) {
                    if ($profileInfo) {
                        if($type == "im") {
                            $profileInfo = htmlentities($profileInfo);
                            $profileMultiVals .= "('$userid','$profileInfo','".$profileInfoMultis['imtype'][$key]."'),";
                        }elseif($type != "imtype") {
                            $profileInfo = htmlentities($profileInfo);
                            $profileMultiVals .= "('$userid','$profileInfo','$type'),";
                        }
                    }
                }
            }
        } 
        $profileMultiVals = substr($profileMultiVals, 0, -1);

        // edit Multis

        foreach ($_POST as $key => $value) {

            if(is_numeric($key)) {
                $value = htmlentities($value);
                $multiEdits[] = array("id"=>$key,"value"=>$value);
            }
            if (preg_match('/im/', $key)) {
                if ($key != "im" && $key != "imtype") {
             // edit Multi Im
                    preg_match("/\d+/",$key,$id);
                   $im = $_POST["im".$id[0]];

                   $imname = htmlentities($im[0]);
                   $imtype = htmlentities($im[1]);
                  $multiEditsIm[] = array("id"=>$id[0],"im"=>$imname,"type"=>$imtype);
                }
            }
        }

        // Construct grand array.
        $profileGrandArray["multiVals"] = $profileMultiVals;
        $profileGrandArray["singleVals"] = $profileSingle;
        $profileGrandArray["multiEdits"] = $multiEdits;
        $profileGrandArray["multiEditsIm"] = $multiEditsIm;

        return $profileGrandArray;
    } */

     function multiDimensionalarrayMap($func,$arr) { 
            $newArr = array(); 
                if (!empty($arr)) { 
                foreach($arr AS $key => $value) {
                    
                $newArr[$key] = (is_array($value) ? $this->multiDimensionalarrayMap($func,$value) : $func($value,false,"UTF-8"));
                    if($newArr[$key] == ""){ // Added to removed empty values
                        unset($newArr[$key]);
                    }
                } 
            } 
            return $newArr; 
          }
          
     function multiDimensionalarrayTrim($func,$arr) { 
            $newArr = array(); 
                if (!empty($arr)) { 
                foreach($arr AS $key => $value) {
                    
                $newArr[$key] = (is_array($value) ? $this->multiDimensionalarrayTrim($func,$value) : $func($value));
                    if($newArr[$key] == ""){ // Added to removed empty values
                        unset($newArr[$key]);
                    }
                } 
            } 
            return $newArr; 
          }          
    
   

}
?>

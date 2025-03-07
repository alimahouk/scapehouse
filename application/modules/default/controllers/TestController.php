<?php

class TestController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH . '/modules/default/layouts/');
    }

    public function indexAction() {

       
        $this->view->headTitle('Scapehouse | Fireing Range =P', 'SET');
        $this->view->pageClass = "corporate test";
       // echo Model_StaticFunctions::salted_sha1("mahouk") . "<br />";
       
       $scapes = new Logged_Model_DbTable_Scape();
       $allScapes = $scapes->getAllScapes();
       
       foreach ($allScapes as $scape){
        $scapes->searchInsert($scape["id"],$scape["content"],$scape["title"]);
       }
        
        if($_GET["command"] == "akayRebuild"){
            
            $filename = sha1(str_shuffle("Note345thatI#@$%#don't4534534wantomod435345ifythe534543534arrayanditsvalu3453453eshou45345345ldntact"));
            
            // Get the old filenames to delete
            $oldfilename = file_get_contents("filename.txt");
            
            // Save new file names
            fopen("filename.txt","w");
            file_put_contents("filename.txt", $filename);
            
            unlink("scripts/{$oldfilename}_O.js");
            
            unlink("stylesheets/css/webkit/{$oldfilename}_sh.css");
            unlink("stylesheets/css/presto/{$oldfilename}_sh.css");
            unlink("stylesheets/css/ie/8/{$oldfilename}_sh.css");
            unlink("stylesheets/css/ie/7/{$oldfilename}_sh.css");
            unlink("stylesheets/css/ie/6/{$oldfilename}_sh.css");
            unlink("stylesheets/css/firefox/3.6/{$oldfilename}_sh.css");
            unlink("stylesheets/css/firefox/3.5/{$oldfilename}_sh.css");
            
         $time = date("l, F d, Y \a\\t g:i a", time());
            
        $commentHeader = "/*
        *  Time: {$time}
        */";
            
            // Deal with the javascript
            fopen("scripts/{$filename}_O.js","w");
    
            $fileContents = file_get_contents("scripts/O.js");
            Model_JSMin::minify($fileContents);
            
            file_put_contents("scripts/{$filename}_O.js", $commentHeader.Model_JSMin::minify($fileContents));
            
            // Deal with the CSS ------------------
            
            fopen("stylesheets/css/webkit/{$filename}_sh.css","w");
            fopen("stylesheets/css/presto/{$filename}_sh.css","w");
            fopen("stylesheets/css/ie/8/{$filename}_sh.css","w");
            fopen("stylesheets/css/ie/7/{$filename}_sh.css","w");
            fopen("stylesheets/css/ie/6/{$filename}_sh.css","w");
            fopen("stylesheets/css/firefox/3.6/{$filename}_sh.css","w");
            fopen("stylesheets/css/firefox/3.5/{$filename}_sh.css","w");


            $webkitCss = array(
                    file_get_contents("stylesheets/css/webkit/design.css"),
                    file_get_contents("stylesheets/css/webkit/emoticonTable.css"),
                    file_get_contents("stylesheets/css/webkit/layout.css"),
                    file_get_contents("stylesheets/css/webkit/main.css"),
                    file_get_contents("stylesheets/css/webkit/scape.css"),
            );

            $prestoCss = array(
                    file_get_contents("stylesheets/css/presto/design.css"),
                    file_get_contents("stylesheets/css/presto/emoticonTable.css"),
                    file_get_contents("stylesheets/css/presto/layout.css"),
                    file_get_contents("stylesheets/css/presto/main.css"),
                    file_get_contents("stylesheets/css/presto/scape.css"),
            );
            $ie8Css = array(
                    file_get_contents("stylesheets/css/ie/8/design.css"),
                    file_get_contents("stylesheets/css/ie/8/emoticonTable.css"),
                    file_get_contents("stylesheets/css/ie/8/layout.css"),
                    file_get_contents("stylesheets/css/ie/8/main.css"),
                    file_get_contents("stylesheets/css/ie/8/scape.css"),
            );
            $ie7Css = array(
                    file_get_contents("stylesheets/css/ie/7/design.css"),
                    file_get_contents("stylesheets/css/ie/7/emoticonTable.css"),
                    file_get_contents("stylesheets/css/ie/7/layout.css"),
                    file_get_contents("stylesheets/css/ie/7/main.css"),
                    file_get_contents("stylesheets/css/ie/7/scape.css"),
            );

            $ie6Css = array(
                    file_get_contents("stylesheets/css/ie/6/design.css"),
                    file_get_contents("stylesheets/css/ie/6/emoticonTable.css"),
                    file_get_contents("stylesheets/css/ie/6/layout.css"),
                    file_get_contents("stylesheets/css/ie/6/main.css"),
                    file_get_contents("stylesheets/css/ie/6/scape.css"),
            );
            $ff3_6Css = array(
                    file_get_contents("stylesheets/css/firefox/3.6/design.css"),
                    file_get_contents("stylesheets/css/firefox/3.6/emoticonTable.css"),
                    file_get_contents("stylesheets/css/firefox/3.6/layout.css"),
                    file_get_contents("stylesheets/css/firefox/3.6/main.css"),
                    file_get_contents("stylesheets/css/firefox/3.6/scape.css"),
            );
            $ff3_5Css = array(
                    file_get_contents("stylesheets/css/firefox/3.5/design.css"),
                    file_get_contents("stylesheets/css/firefox/3.5/emoticonTable.css"),
                    file_get_contents("stylesheets/css/firefox/3.5/layout.css"),
                    file_get_contents("stylesheets/css/firefox/3.5/main.css"),
                    file_get_contents("stylesheets/css/firefox/3.5/scape.css"),
            );


            foreach($webkitCss as $css) {
                $webkitCssComp .= Model_CssMin::minify($css);
            }
            foreach($prestoCss as $css) {
                $prestoCssComp .= Model_CssMin::minify($css);
            }
            foreach($ie8Css as $css) {
                $ie8CssComp .= Model_CssMin::minify($css);
            }
            foreach($ie7Css as $css) {
                $ie7CssComp .= Model_CssMin::minify($css);
            }
            foreach($ie6Css as $css) {
                $ie6CssComp .= Model_CssMin::minify($css);
            }
            foreach($ff3_6Css as $css) {
                $ff3_6CssComp .= Model_CssMin::minify($css);
            }
            foreach($ff3_5Css as $css) {
                $ff3_5CssComp .= Model_CssMin::minify($css);
            }

         file_put_contents("stylesheets/css/webkit/{$filename}_sh.css",$webkitCssComp);
         file_put_contents("stylesheets/css/presto/{$filename}_sh.css",$prestoCssComp);
         file_put_contents("stylesheets/css/ie/8/{$filename}_sh.css",$ie8CssComp);
         file_put_contents("stylesheets/css/ie/7/{$filename}_sh.css",$ie7CssComp);
         file_put_contents("stylesheets/css/ie/6/{$filename}_sh.css",$ie6CssComp);
         file_put_contents("stylesheets/css/firefox/3.6/{$filename}_sh.css",$ff3_6CssComp);
         file_put_contents("stylesheets/css/firefox/3.5/{$filename}_sh.css",$ff3_5CssComp);
            
            //-------------------------
            
            echo "<h1>The deed is done...</h1>";
            
        }
        
        // OTHER STUFF--------
        if($_GET["command"] == "akayRebuildJavascript") {
            fopen("scripts/ajax/dsajax.js","w");
            fopen("scripts/ds.js","w");

            $jsfiles = array(
                    file_get_contents("scripts/ajax/general.js"),
                    file_get_contents("scripts/ajax/comment.js"),
                    file_get_contents("scripts/ajax/likesDislikes.js"),
                    file_get_contents("scripts/ajax/notifs.js"),
                    file_get_contents("scripts/ajax/photo.js"),
                    file_get_contents("scripts/ajax/privacy.js"),
                    file_get_contents("scripts/ajax/profile.js"),
                    file_get_contents("scripts/ajax/reply.js"),
                    file_get_contents("scripts/ajax/scape.js"),
                    file_get_contents("scripts/ajax/search.js"),
                    file_get_contents("scripts/ajax/signup.js"),
                    file_get_contents("scripts/ajax/watchlist.js")
            );

            $jsGen = array(
                    file_get_contents("scripts/functions.js"),
                    file_get_contents("scripts/libraries/ajaxupload.js"),
                    file_get_contents("scripts/libraries/jqueryplugins/jqCookie.js"),
                    file_get_contents("scripts/libraries/jqueryplugins/jquery.placeholder.js"),
                    file_get_contents("scripts/privacy.js"),
                    file_get_contents("scripts/returnSubmit.js"),
                    file_get_contents("scripts/signup.js"),
                    file_get_contents("scripts/detectTimezone.js"),
                    file_get_contents("scripts/searchSuggestionConfig.js")


            );

            echo "<script>";
            foreach($jsfiles as $js) {
                $dsajax .= Model_JSMin::minify($js);
            }
            foreach($jsGen as $js) {
                $dsjs .= Model_JSMin::minify($js);
            }
            echo "</script>";


            echo file_put_contents("scripts/ajax/dsajax.js", $dsajax) . "<br>";
            echo file_put_contents("scripts/ds.js", $dsjs). "<br>";

            echo "<h1>Javascript Rebuilt<h2>";

        }

        if($_GET["command"] == "akayRebuildCss") {

            fopen("stylesheets/css/webkit/ds.css","w");
            fopen("stylesheets/css/presto/ds.css","w");
            fopen("stylesheets/css/ie/8/ds.css","w");
            fopen("stylesheets/css/ie/7/ds.css","w");
            fopen("stylesheets/css/ie/6/ds.css","w");
            fopen("stylesheets/css/firefox/3.6/ds.css","w");
            fopen("stylesheets/css/firefox/3.5/ds.css","w");


            $webkitCss = array(
                    file_get_contents("stylesheets/css/webkit/design.css"),
                    file_get_contents("stylesheets/css/webkit/emoticonTable.css"),
                    file_get_contents("stylesheets/css/webkit/layout.css"),
                    file_get_contents("stylesheets/css/webkit/main.css"),
                    file_get_contents("stylesheets/css/webkit/scape.css"),
            );

            $prestoCss = array(
                    file_get_contents("stylesheets/css/presto/design.css"),
                    file_get_contents("stylesheets/css/presto/emoticonTable.css"),
                    file_get_contents("stylesheets/css/presto/layout.css"),
                    file_get_contents("stylesheets/css/presto/main.css"),
                    file_get_contents("stylesheets/css/presto/scape.css"),
            );
            $ie8Css = array(
                    file_get_contents("stylesheets/css/ie/8/design.css"),
                    file_get_contents("stylesheets/css/ie/8/emoticonTable.css"),
                    file_get_contents("stylesheets/css/ie/8/layout.css"),
                    file_get_contents("stylesheets/css/ie/8/main.css"),
                    file_get_contents("stylesheets/css/ie/8/scape.css"),
            );
            $ie7Css = array(
                    file_get_contents("stylesheets/css/ie/7/design.css"),
                    file_get_contents("stylesheets/css/ie/7/emoticonTable.css"),
                    file_get_contents("stylesheets/css/ie/7/layout.css"),
                    file_get_contents("stylesheets/css/ie/7/main.css"),
                    file_get_contents("stylesheets/css/ie/7/scape.css"),
            );

            $ie6Css = array(
                    file_get_contents("stylesheets/css/ie/6/design.css"),
                    file_get_contents("stylesheets/css/ie/6/emoticonTable.css"),
                    file_get_contents("stylesheets/css/ie/6/layout.css"),
                    file_get_contents("stylesheets/css/ie/6/main.css"),
                    file_get_contents("stylesheets/css/ie/6/scape.css"),
            );
            $ff3_6Css = array(
                    file_get_contents("stylesheets/css/firefox/3.6/design.css"),
                    file_get_contents("stylesheets/css/firefox/3.6/emoticonTable.css"),
                    file_get_contents("stylesheets/css/firefox/3.6/layout.css"),
                    file_get_contents("stylesheets/css/firefox/3.6/main.css"),
                    file_get_contents("stylesheets/css/firefox/3.6/scape.css"),
            );
            $ff3_5Css = array(
                    file_get_contents("stylesheets/css/firefox/3.5/design.css"),
                    file_get_contents("stylesheets/css/firefox/3.5/emoticonTable.css"),
                    file_get_contents("stylesheets/css/firefox/3.5/layout.css"),
                    file_get_contents("stylesheets/css/firefox/3.5/main.css"),
                    file_get_contents("stylesheets/css/firefox/3.5/scape.css"),
            );


            foreach($webkitCss as $css) {
                $webkitCssComp .= Model_CssMin::minify($css);
            }
            foreach($prestoCss as $css) {
                $prestoCssComp .= Model_CssMin::minify($css);
            }
            foreach($ie8Css as $css) {
                $ie8CssComp .= Model_CssMin::minify($css);
            }
            foreach($ie7Css as $css) {
                $ie7CssComp .= Model_CssMin::minify($css);
            }
            foreach($ie6Css as $css) {
                $ie6CssComp .= Model_CssMin::minify($css);
            }
            foreach($ff3_6Css as $css) {
                $ff3_6CssComp .= Model_CssMin::minify($css);
            }
            foreach($ff3_5Css as $css) {
                $ff3_5CssComp .= Model_CssMin::minify($css);
            }

            echo file_put_contents("stylesheets/css/webkit/ds.css",$commentHeader.$webkitCssComp) . "<br>";
            echo file_put_contents("stylesheets/css/presto/ds.css",$commentHeader.$prestoCssComp) . "<br>";
            echo file_put_contents("stylesheets/css/ie/8/ds.css",$commentHeader.$ie8CssComp) . "<br>";
            echo file_put_contents("stylesheets/css/ie/7/ds.css",$commentHeader.$ie7CssComp) . "<br>";
            echo file_put_contents("stylesheets/css/ie/6/ds.css",$commentHeader.$ie6CssComp) . "<br>";
            echo file_put_contents("stylesheets/css/firefox/3.6/ds.css",$commentHeader.$ff3_6CssComp) . "<br>";
            echo file_put_contents("stylesheets/css/firefox/3.5/ds.css",$commentHeader.$ff3_5CssComp) . "<br>";

            echo "<h1>Css Rebuilt<h2>";

        }
    }
}







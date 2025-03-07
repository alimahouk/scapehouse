<?php
class Model_StyleSheets {

    private $exAdd;

    function serveStyles() {

        if($_SERVER["SERVER_NAME"] != "localhost" && $_SERVER["SERVER_NAME"] != "designoscape.ath.cx"){
       // $this->exAdd = "http://infobridge.host56.com";
        }

        $filename = file_get_contents("filename.txt");
        
        $browser = new Model_Browser();
        
        switch (true) {
            case ($browser->getBrowser() == Model_Browser::BROWSER_IE && $browser->getVersion() <= 6):
                return <<<STYLESHEET
<link href="$this->exAdd/stylesheets/css/ie/6/{$filename}_sh.css" media="screen" rel="stylesheet" type="text/css" >
STYLESHEET;
                break;

            case ($browser->getBrowser() == Model_Browser::BROWSER_IE && $browser->getVersion() == 7):
                return <<<STYLESHEET
<link href="$this->exAdd/stylesheets/css/ie/7/{$filename}_sh.css" media="screen" rel="stylesheet" type="text/css" >
STYLESHEET;
                break;
            case ($browser->getBrowser() == Model_Browser::BROWSER_IE && $browser->getVersion() >= 8):
                return <<<STYLESHEET
<link href="$this->exAdd/stylesheets/css/ie/8/{$filename}_sh.css" media="screen" rel="stylesheet" type="text/css" >
STYLESHEET;
                break;
            case ($browser->getBrowser() == Model_Browser::BROWSER_FIREFOX && $browser->getVersion() <= 3.5 || $browser->getBrowser() == Model_Browser::BROWSER_MOZILLA):
                return <<<STYLESHEET
<link href="$this->exAdd/stylesheets/css/firefox/3.5/{$filename}_sh.css" media="screen" rel="stylesheet" type="text/css" >
STYLESHEET;
                break;
            case ($browser->getBrowser() == Model_Browser::BROWSER_FIREFOX && $browser->getVersion() >= 3.6):
                return <<<STYLESHEET
<link href="$this->exAdd/stylesheets/css/firefox/3.6/{$filename}_sh.css" media="screen" rel="stylesheet" type="text/css" >
STYLESHEET;
                break;
            case ($browser->getBrowser() == Model_Browser::BROWSER_OPERA):
                return <<<STYLESHEET
<link href="$this->exAdd/stylesheets/css/presto/{$filename}_sh.css" media="screen" rel="stylesheet" type="text/css" >
STYLESHEET;
                break;
            case ($browser->getBrowser() == Model_Browser::BROWSER_SAFARI  ||
                    $browser->getBrowser() == Model_Browser::BROWSER_CHROME||
                    $browser->getBrowser() == Model_Browser::BROWSER_ICAB||
                    $browser->getBrowser() == Model_Browser::BROWSER_OMNIWEB||
                    $browser->getBrowser() == Model_Browser::BROWSER_IPHONE||
                    $browser->getBrowser() == Model_Browser::BROWSER_IPOD
            ):
                return <<<STYLESHEET
<link href="$this->exAdd/stylesheets/css/webkit/{$filename}_sh.css" media="screen" rel="stylesheet" type="text/css" >
STYLESHEET;
                break;
            default:
                return <<<STYLESHEET
<link href="$this->exAdd/stylesheets/css/webkit/{$filename}_sh.css" media="screen" rel="stylesheet" type="text/css" >
STYLESHEET;
                break;

        }
    }

}
?>

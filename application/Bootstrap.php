<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    function _initRouter() {

        if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
            ob_start("ob_gzhandler");
        }
        else {
            ob_start();
        }


        // Dont touch --------
        $front = new Zend_Controller_Router_Rewrite();
        $request = new Zend_Controller_Request_Http();
        $front = $front->getFrontController();
        $router = $front->getRouter();
        $router->addDefaultRoutes();
        
        // -------------------------------------


        $index = new Zend_Controller_Router_Route("/index", array("controller" => "index", "action" => "index"));
        $router->addRoute("index", $index);
        $router->route($request);

        $signup = new Zend_Controller_Router_Route("/signup", array("controller" => "signup", "action" => "index"));
        $router->addRoute("signup", $signup);
        $router->route($request);

        $login = new Zend_Controller_Router_Route("/login", array("controller" => "login", "action" => "index"));
        $router->addRoute("login", $login);
        $router->route($request);

        $about = new Zend_Controller_Router_Route("/about", array("controller" => "about", "action" => "index"));
        $router->addRoute("about", $about);
        $router->route($request);

        $contact = new Zend_Controller_Router_Route("/contact", array("controller" => "contact", "action" => "index"));
        $router->addRoute("contact", $contact);
        $router->route($request);

        $privacy = new Zend_Controller_Router_Route("/privacy", array("controller" => "privacy", "action" => "index"));
        $router->addRoute("privacy", $privacy);
        $router->route($request);

        $terms = new Zend_Controller_Router_Route("/terms", array("controller" => "terms", "action" => "index"));
        $router->addRoute("terms", $terms);
        $router->route($request);

        $test = new Zend_Controller_Router_Route("/test", array("controller" => "test", "action" => "index"));
        $router->addRoute("test", $test);
        $router->route($request);

        $verify = new Zend_Controller_Router_Route("/verify", array("controller" => "verify", "action" => "index"));
        $router->addRoute("verify", $verify);
        $router->route($request);

        // help -----

        $help = new Zend_Controller_Router_Route("/help", array("controller" => "help", "action" => "index"));
        $router->addRoute("help", $help);
        $router->route($request);

        $helpConsole = new Zend_Controller_Router_Route("/help/console", array("controller" => "help", "action" => "console"));
        $router->addRoute("helpConsole", $helpConsole);
        $router->route($request);

        $helpPhoto = new Zend_Controller_Router_Route("/help/photos", array("controller" => "help", "action" => "photos"));
        $router->addRoute("helpPhoto", $helpPhoto);
        $router->route($request);

        $helpScape = new Zend_Controller_Router_Route("/help/scapes", array("controller" => "help", "action" => "scapes"));
        $router->addRoute("helpScape", $helpScape);
        $router->route($request);

        $helpEmo = new Zend_Controller_Router_Route("/help/scapes/emos", array("controller" => "help", "action" => "emos"));
        $router->addRoute("helpEmo", $helpEmo);
        $router->route($request);

        $helpPrivacy = new Zend_Controller_Router_Route("/help/privacy", array("controller" => "help", "action" => "privacy"));
        $router->addRoute("helpPrivacy", $helpPrivacy);
        $router->route($request);

        $helpFaqs = new Zend_Controller_Router_Route("/help/faqs", array("controller" => "help", "action" => "faqs"));
        $router->addRoute("helpFaqs", $helpFaqs);
        $router->route($request);
        
        $helpIssues = new Zend_Controller_Router_Route("/help/issues", array("controller" => "help", "action" => "issues"));
        $router->addRoute("helpIssues", $helpIssues);
        $router->route($request);
        
        $helpWatchist = new Zend_Controller_Router_Route("/help/watchlist", array("controller" => "help", "action" => "watchlist"));
        $router->addRoute("helpWatchist", $helpWatchist);
        $router->route($request);
        
        $helpNoteboard = new Zend_Controller_Router_Route("/help/noteboard", array("controller" => "help", "action" => "noteboard"));
        $router->addRoute("helpNoteboard", $helpNoteboard);
        $router->route($request);

        // help -----
        $search = new Zend_Controller_Router_Route("/search", array("controller" => "search", "action" => "index"));
        $router->addRoute("search", $search);
        $router->route($request);




        if ($router->getCurrentRouteName() != "index" &&
                $router->getCurrentRouteName() != "signup" &&
                $router->getCurrentRouteName() != "login" &&
                $router->getCurrentRouteName() != "about" &&
                $router->getCurrentRouteName() != "contact" &&
                $router->getCurrentRouteName() != "privacy" &&
                $router->getCurrentRouteName() != "terms" &&
                $router->getCurrentRouteName() != "help" &&
                $router->getCurrentRouteName() != "helpConsole" &&
                $router->getCurrentRouteName() != "helpPhoto" &&
                $router->getCurrentRouteName() != "helpScape" &&
                $router->getCurrentRouteName() != "helpEmo" &&
                $router->getCurrentRouteName() != "helpPrivacy" &&
                $router->getCurrentRouteName() != "helpFaqs" &&
                $router->getCurrentRouteName() != "helpIssues" &&
                $router->getCurrentRouteName() != "search" &&
                $router->getCurrentRouteName() != "test"   &&
                $router->getCurrentRouteName() != "helpWatchist"  &&
                $router->getCurrentRouteName() != "helpNoteboard"  &&
                $router->getCurrentRouteName() != "verify"
        ) {



            $username = new Zend_Controller_Router_Route("/:username", array("module" => "logged", "controller" => "index", "action" => "index"));
            $router->addRoute("username", $username);

            $home = new Zend_Controller_Router_Route("/:username/home", array("module" => "logged", "controller" => "home", "action" => "index"));
            $router->addRoute("home", $home);

            $profile = new Zend_Controller_Router_Route("/:username/profile", array("module" => "logged", "controller" => "profile", "action" => "index"));
            $router->addRoute("profile", $profile);

            $profileEdit = new Zend_Controller_Router_Route("/:username/profile/edit", array("module" => "logged", "controller" => "profile", "action" => "edit"));
            $router->addRoute("edit", $profileEdit);

            $scape = new Zend_Controller_Router_Route("/:username/scape/:id", array("module" => "logged", "controller" => "scape", "action" => "index"), array("id" => "\d+"));
            $router->addRoute("scape", $scape);

            $notifs = new Zend_Controller_Router_Route("/:username/notifs", array("module" => "logged", "controller" => "notifs", "action" => "index"));
            $router->addRoute("notifs", $notifs);
            
            $scapeboxOther = new Zend_Controller_Router_Route("/:username/scapebox/other", array("module" => "logged", "controller" => "scapebox", "action" => "other"));
            $router->addRoute("scapeboxOther", $scapeboxOther);
            
            $scapeboxMe = new Zend_Controller_Router_Route("/:username/scapebox/me", array("module" => "logged", "controller" => "scapebox", "action" => "me"));
            $router->addRoute("scapeboxMe", $scapeboxMe);
            
            //-- Console ---

            $console = new Zend_Controller_Router_Route("/:username/console", array("module" => "logged", "controller" => "console", "action" => "index"));
            $router->addRoute("console", $console);

            $consoleAccount = new Zend_Controller_Router_Route("/:username/console/account", array("module" => "logged", "controller" => "console", "action" => "account"));
            $router->addRoute("consoleAccount", $consoleAccount);

            $consoleStats = new Zend_Controller_Router_Route("/:username/console/stats", array("module" => "logged", "controller" => "console", "action" => "stats"));
            $router->addRoute("consoleStats", $consoleStats);

            $consolePrivacy = new Zend_Controller_Router_Route("/:username/console/privacy", array("module" => "logged", "controller" => "console", "action" => "privacy"));
            $router->addRoute("consolePrivacy", $consolePrivacy);

            $consoleNotifs = new Zend_Controller_Router_Route("/:username/console/notifs", array("module" => "logged", "controller" => "console", "action" => "notifs"));
            $router->addRoute("consoleNotifs", $consoleNotifs);

            $consoleAbout = new Zend_Controller_Router_Route("/:username/console/about", array("module" => "logged", "controller" => "console", "action" => "about"));
            $router->addRoute("consoleAbout", $consoleAbout);
            
            $consolePersonalize = new Zend_Controller_Router_Route("/:username/console/personalize", array("module" => "logged", "controller" => "console", "action" => "personalize"));
            $router->addRoute("consolePersonalize", $consolePersonalize);

            //-- Console ---
            $watchlist = new Zend_Controller_Router_Route("/:username/watchlist", array("module" => "logged", "controller" => "watchlist", "action" => "index"));
            $router->addRoute("watchlist", $watchlist);

            $watchers = new Zend_Controller_Router_Route("/:username/watchlist/watchers", array("module" => "logged", "controller" => "watchlist", "action" => "watchers"));
            $router->addRoute("watchers", $watchers);

            $watching = new Zend_Controller_Router_Route("/:username/watchlist/watching", array("module" => "logged", "controller" => "watchlist", "action" => "watching"));
            $router->addRoute("watching", $watching);

            $gallery = new Zend_Controller_Router_Route("/:username/gallery", array("module" => "logged", "controller" => "gallery", "action" => "index"));
            $router->addRoute("gallery", $gallery);

            $photo = new Zend_Controller_Router_Route("/:username/gallery/photo/:id", array("module" => "logged", "controller" => "gallery", "action" => "photo"), array("id" => "\d+"));
            $router->addRoute("photo", $photo);
            
            $random = new Zend_Controller_Router_Route("/random", array("module" => "logged", "controller" => "random", "action" => "index"));
            $router->addRoute("random", $random);
        }

    }
    
    function _initAutoload() {

        $modelLoader = new Zend_Application_Module_Autoloader(
                array(
                        "namespace" => "",
                        "basePath" => APPLICATION_PATH . "/modules/default"));

        return $modelLoader;
    }
    function _initSession() {
        Zend_Session::start();
        $GLOBALS["session"] = Model_StaticFunctions::sessionContent();
    }

    function _initHeaders() {
        header('Content-type: text/html; charset=utf-8');
    }

    function _initViewHelpers() {
        $front = new Zend_Controller_Router_Rewrite();
        $front = $front->getFrontController();
        $router = $front->getRouter();

        $this->bootstrap("layout");
        $layout = $this->getResource("layout");

    }

}


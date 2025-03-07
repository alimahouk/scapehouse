<?php

class Model_Signup {

    // Define properties.
    public $firstname;

    public $lastname;

    public $email;

    public $username;

    public $password;

    public $captchaChallange;

    public $captchaResponce;

    public $signupErrors;

    function  __construct() {

        $this->firstname = $_POST['firstname'];
        $this->lastname = $_POST['lastname'];
        $this->email = $_POST['email'];
        $this->username = $_POST['username'];
        $this->password = $_POST['password'];
        $this->captchaChallange = $_POST["recaptcha_challange_field"];
        $this->captchaResponce = $_POST["recaptcha_response_field"];

    }
    
    function signup() {

        $this->standardizeInput()->validateName()->validateEmail()->validateUsername()->validatePassword()->validatePassword();

        if (!$this->signupErrors) {
            $this->success = true;

            $createUser = new Model_DbTable_Users();
            // returns a userid
            return $createUser->createUser($this->firstname,$this->lastname,$this->email,$this->username,$this->password);
        }else{
            return false;
        }

    }

    function validateName() {

        $validateName = new Zend_Validate_Alpha();
        $validateName->setAllowWhiteSpace(true);
        $lengthCheck = new Zend_Validate_StringLength();

        $this->standardizeInput();

        $lengthCheck->setMin(2)->setMax(20);
        if(!$validateName->isValid($this->firstname) || !$lengthCheck->isValid($this->firstname)) {
            $this->signupErrors["firstnameErr"] = true;
        }

        if(!$validateName->isValid($this->lastname) || !$lengthCheck->isValid($this->lastname)) {
            $this->signupErrors["lastnameErr"] = true;
        }

        return $this;

    }

    function validateEmail() {

        $validateEmail = new Zend_Validate_EmailAddress();

        if(!$validateEmail->isValid($this->email)) {
            $this->signupErrors["emailErr"] = true;
        }else {
            $user = new Model_DbTable_Users();
            if($user->checkUniqueEmail($this->email)) {
                $this->signupErrors["emailExistsErr"] = true;
            }
        }

        return $this;

    }

    function validateUsername() {

        $lengthCheck = new Zend_Validate_StringLength();

        $restricted = array(
                "index",
                "signup",
                "login",
                "about",
                "contact",
                "privacy",
                "terms",
                "test",
                "verify",
                "mobile",
                "all",
                "help");

        if(array_search(strtolower($this->username), $restricted) !== FALSE ||
                !preg_match("/^[a-zA-Z0-9\._-]{2,20}$/",$this->username) ||
                !$lengthCheck->isValid($this->username)) {

            $this->signupErrors["usernameErr"] = true;
        }else {
            $user = new Model_DbTable_Users();

            if ($user->checkUniqueUser($this->username)) {
                $this->signupErrors["usernamExistsErr"] = true;
            }
        }

        return $this;

    }

    function validatePassword() {

        $lengthCheck = new Zend_Validate_StringLength();

        $lengthCheck->setMin(6);
        if(!$lengthCheck->isValid($this->password)) {

            $this->signupErrors["passwordErr"] = true;
        }

        return $this;

    }

    function validateCaptcha() {

        $publickey = "6LcMtLkSAAAAAKh6tA85667nh0B8CF9NJ63KLVxE";
        $privatekey = "6LcMtLkSAAAAAAuB_HpkGyZBZnpHDlboQ9pbK33X";

        $recaptcha = new Zend_Service_ReCaptcha($publickey, $privatekey);

        $result = $recaptcha->verify(
                $this->captchaChallange,
                $this->captchaResponce
        );

        if (!$result->isValid()) {
            // the code was incorrect
            $this->signupErrors['captchaErr'] = true;
        }

        return $this;
    }

    function standardizeInput() {

        $this->firstname = trim($this->firstname);
        $this->firstname = strtolower($this->firstname);
        $this->firstname = ucwords($this->firstname);
        $this->firstname = Model_StaticFunctions::stripExtraSpace($this->firstname);

        $this->lastname = trim($this->lastname);
        $this->lastname = strtolower($this->lastname);
        $this->lastname = ucwords($this->lastname);
        $this->lastname = Model_StaticFunctions::stripExtraSpace($this->lastname);

        $this->email = trim($this->email);
        $this->username = trim($this->username);

        return $this;

    }

}




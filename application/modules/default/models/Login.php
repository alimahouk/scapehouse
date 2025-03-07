<?php

class Model_Login {

    public $username;

    public $email;

    public $password;

    public $rememberMe;

    public $loginErrors = array();

    function dsLogin ($noSha1 = FALSE) {

        if (!empty($this->rememberMe)) {
            // Set cookie for 2 weeks
            Zend_Session::rememberMe(604800*2);
        } else {

            Zend_Session::forgetMe();
        }

        // if an email is recived instead of a username : use that instead of username.


        if (isset($this->email)) {

            $authAdapter = $this->getAuthAdapterEmail();

            $this->password = Model_StaticFunctions::salted_sha1($this->password);

            $authAdapter->setIdentity($this->email)->setCredential($this->password);

            $auth = Zend_Auth::getInstance();
            $result = $auth->authenticate($authAdapter);

            if ($result->isValid()) {
                $identity = $authAdapter->getResultRowObject();

                $authStorage = $auth->getStorage();
                $authStorage->write($identity);

                return true;

            }
        }
        else {

            if ($this->dsNotEmpty() == FALSE) {
                if(!$noSha1) {
                    $this->password = Model_StaticFunctions::salted_sha1($this->password);
                }


                $authAdapter = $this->getAuthAdapterUsername();

                $authAdapter->setIdentity($this->username)->setCredential($this->password);

                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $identity = $authAdapter->getResultRowObject(array("id","firstname","lastname","username"));
                    $identity->fullname = $identity->firstname ." ". $identity->lastname;

                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);

                    return true;

                } else {

                    return false;


                }
            }

        }

    }

    function getAuthAdapterUsername () {

        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('users')->setIdentityColumn('username')->setCredentialColumn('password');

        return $authAdapter;

    }

    function getAuthAdapterEmail () {

        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName('users')->setIdentityColumn('email')->setCredentialColumn('password');

        return $authAdapter;

    }

    function dsNotEmpty ()
    // Functions checks to see if any of the input values have been left blank or not.
    {

        $notEmpty = new Zend_Validate_NotEmpty();
        $errorCount = 0;

        if (! $notEmpty->isValid($this->username)) {
            $this->loginErrors['usernameBlank'] = 'username';
            $errorCount = 1;
        }

        if (! $notEmpty->isValid($this->password)) {
            $this->loginErrors['passwordBlank'] = 'password';
            $errorCount = 1;
        }

        if ($errorCount == 0) { // No fields are blank.
            return FALSE;
        } else {

            return TRUE;
        }

    }
}

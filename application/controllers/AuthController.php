<?php

class AuthController extends My_Controller_Action
{


    public function indexAction()
    {

    }

    public function silentAction()
    {
        $this->disableLayout()->disableView();



        $username = $this->getRequest()->getParam("username");
        $password = $this->getRequest()->getParam("password");

        $auth = Zend_Auth::getInstance();

        $myAdapter = new My_Adapter();
        $myAdapter->setEmailPassword($username, $password);

        $result = $auth->authenticate($myAdapter);

        if ($result->isValid()) {
            $identity = Zend_Auth::getInstance()->getIdentity();
            setcookie("_tAppCookie", $identity['email'], time() + 3600 * 24 * 14, '/');
            $this->redirect("/");
        }

        $this->redirect("/auth/error");

    }


    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        setcookie("_tAppCookie", '', time(), '/');
        $this->redirect("/");
    }


    public function errorAction()
    {

    }


    public function signupAction()
    {

        if ($this->getRequest()->isPost()) {
       //     My_Log_Me::Log($this->getRequest()->getParams());
            $params = $this->getRequest()->getParams();

            if (isset($params["adduser"])) {
                $tableUser = new Table_User();

                /** @var Model_User $user */
                $user = $tableUser->createRow($params);

                $password = $params["password"];
                $user->setPassword(md5($password));

                try {
                    $user->save();
                } catch (Exception $e) {
                    Zend_Debug::dump($e->getMessage());
                    return;
                }

            }

                  $this->redirect("/");
        }
    }

    public function checkUsernameAction()
    {
        $this->disableLayout()->disableView();
        $id = $this->getRequest()->getParam("id");
        $username = $this->getRequest()->getParam("username");

        $userTable = new Table_User();

        if ( ! is_null($user = $userTable->getByUsername($username))) {
            if(! is_null( $id ) &&  $id != $user->getId() ){
                $this->sendJson("User already exists");}
             if( is_null( $id ) &&  $username == $user->getUsername() ){
                    $this->sendJson("User already exists");
             }
        }


        // user do not exists with this username !
        $this->sendJson(true);
    }

    public function checkEmailAction()
    {

        $id = $this->getRequest()->getParam("id");
        $email = $this->getRequest()->getParam("email");

        $userTable = new Table_User();
        if (!is_null($user = $userTable->getByEmail($email))) {
            if(! is_null( $id ) &&  $id != $user->getId() ){
                $this->sendJson("Email already taken");
            }
            if( is_null( $id ) &&  $email == $user->getEmail() ){
                $this->sendJson("Email already taken");
            }
        }

        // user do not exists with this email !
        $this->sendJson(true);
    }

}


<?php

class AuthController extends My_Controller_Action
{


    public function indexAction()
    {

    }

    public function checkUsernameAction()
    {
        $this->disableLayout()->disableView();
        $id = $this->getRequest()->getParam("id");
        $username = strtolower($this->getRequest()->getParam("username"));

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
        $email = strtolower($this->getRequest()->getParam("email"));

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

//autentinficarea prin login sau autologin dupa activare cont
    public function silentAction()
    {
        $this->disableLayout()->disableView();

        $username = $this->getRequest()->getParam("username");
        $password = $this->getRequest()->getParam("password");


        $auth = Zend_Auth::getInstance();

        $myAdapter = new My_Adapter();

        if ( isset ($password))
        {
            $myAdapter->setEmailPassword($username, $password);
        }
        else
        {
            $myAdapter->setUser($username);
        }
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

            $params = $this->getRequest()->getParams();

            if (isset($params["adduser"])) {
                $tableUser = new Table_User();

                /** @var Model_User $user */
                $user = $tableUser->createRow($params);

                $password = $params["password"];
                $user->setPassword(md5($password));

                try {
                    $user->save();
                }


                catch (Exception $e) {
                        Zend_Debug::dump($e->getMessage());
                        return;
                }

                require_once("/newphotosave.php");

                $this->view->assign("emailto",$user->getEmail());


                $this->redirect("/auth/signup-notify?username=".$user->getUsername()."&goto=/auth/logout");
            }

        }
    }

    public function signupNotifyAction(){

        $username=$this->getRequest()->getParam("username");
        $emailto=$this->getRequest()->getParam("emailto");
        if (! is_null($username))
        {
            $usertable=new Table_User();
            $user=$usertable->getByUsername($username);
            $emailto=$user->getEmail();
            $this->view->assign("emailto",$emailto);
        }
        $this->view->assign("emailto",$emailto);

        $mail=new My_HtmlMailer();
        $mail->sendActivationCode($username);

        // goto=/user/index sent from auth/signup
        // goto=/user/index sent from user/add

        $this->view->assign("goto",$this->getRequest()->getParam("goto"));
        //$this->redirect($this->getRequest()->getParam("goto"));



    }


    public function activateAction(){

        $activation_code = $this->getRequest()->getParam("activation_code");
        $username = $this->getRequest()->getParam("username");
        $this->view->assign("activation_code",$activation_code );


        $user=new Table_User();
        if ($user->getByUsername($username)->getActivationCode()==$activation_code)
        {

            $user_del_code=$user->getByUsername($username);
            $user_del_code->setActivationCode("");
            $user_del_code->save();
            Zend_Auth::getInstance()->clearIdentity();
            setcookie("_tAppCookie", '', time(), '/');
            $this->view->assign("activated",true);
            $this->view->assign("username",$user->getByUsername($username)->getEmail() );
            $this->view->assign("password",$user->getByUsername($username)->getPassword());

        }
        else
            $this->view->assign("activated",false);


    }

/*    public function activateAction(){

        $username = $this->getRequest()->getParam("username");
        $activationCode = $this->getRequest()->getParam("activation_code");


        $auth = Zend_Auth::getInstance();


        $myAdapter = new My_Adapter();
        $myAdapter->setUsernameActivationCode( $username, $activationCode);

        $result = $auth->authenticate($myAdapter);

        if ( $result->isValid() ) {
            $identity = Zend_Auth::getInstance()->getIdentity();
            setcookie("_tAppCookie", $identity['email'], time() + 3600 * 24 * 14, '/');
            $this->redirect("/");
        }

        $this->view->assign("activated",false);

    }*/

    public function sendnotifyRegistrationAction(){

        $this->view->assign("username",$this->getRequest()->getParam("username"));


    }
}


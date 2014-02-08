<?php

class AuthController extends My_Controller_Action
{

    public function indexAction()
    { /*  var  Model_user %usertable */
    $this->disableLayout()->disableView();
    if (isset($_COOKIE["_tAppCookie"]) )
    {
        $email_aut=$_COOKIE["_tAppCookie"];

       $usertable=new Table_User();
        $user=$usertable->getByEmail($email_aut);
        $password=$user["password"];
        $email=$user["email"];


        $auth = Zend_Auth::getInstance();

        $myAdapter = new My_Adapter();
        $myAdapter->setEmailPassword($email,"kaolas");  //nu merge $password pt ca e md5

        $result = $auth->authenticate($myAdapter);


        if( $result->isValid() ){

          $this->redirect("/user");
        }
         else
           $this->redirect("/");

    }
    else
      $this->redirect("/");
    }
    public function silentAction(){
        $this->disableLayout()->disableView();

        Zend_Debug::dump($this->getRequest()->getParams());

        $username = $this->getRequest()->getParam("username");
        $password = $this->getRequest()->getParam("password");

        $auth = Zend_Auth::getInstance();

        $myAdapter = new My_Adapter();
        $myAdapter->setEmailPassword($username,$password);

        $result = $auth->authenticate($myAdapter);

        if( $result->isValid() ){
            $identity = Zend_Auth::getInstance()->getIdentity();
            setcookie("_tAppCookie",$identity['email'],time()+3600*24*14);
            $this->redirect("/");
        }

        $this->redirect("/auth/error");

    }



    public function logoutAction(){
        Zend_Auth::getInstance()->clearIdentity();
        setcookie("_tAppCookie",'',time());
        $this->redirect("/");
    }


    public function errorAction(){

    }

}


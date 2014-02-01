<?php

class AuthController extends My_Controller_Action
{


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
            $this->redirect("/");
        }

        $this->redirect("/auth/error");

    }



    public function logoutAction(){
        Zend_Auth::getInstance()->clearIdentity();

        $this->redirect("/");
    }


    public function errorAction(){

    }

}


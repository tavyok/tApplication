<?php

class IndexController extends My_Controller_Action
{


    public function indexAction()
    {
        // action body

        $this->view->assign("logo","Login please!");

        $auth = Zend_Auth::getInstance();

        if( $auth->hasIdentity() ){
         //   My_Log_Me::Log($auth->getIdentity());
            if ($auth->getIdentity()["role"]==Table_User::ROLE_USER)
                $this->redirect("/any-user");
            if ($auth->getIdentity()["role"]==Table_User::ROLE_ADMIN)
                $this->redirect("/user");
        }

    }


    public function errorAction(){


    }

}


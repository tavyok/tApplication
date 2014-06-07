<?php

class IndexController extends My_Controller_Action
{


    public function indexAction()
    {


        $this->view->assign("logo","Login please!");

        $auth = Zend_Auth::getInstance();

        if( $auth->hasIdentity() ){

            $usertable=new Table_User();
            $username=$auth->getIdentity()["username"];

            $user=$usertable->getByUsername($username);


            if (($user->getActivationCode())!="")
            {

                $this->redirect("/auth/sendnotify-registration?username=".$username);

            }
            else
            {
            if ($auth->getIdentity()["role"]==Table_User::ROLE_USER)
                $this->redirect("/any-user");
            if ($auth->getIdentity()["role"]==Table_User::ROLE_ADMIN)
                $this->redirect("/user");
            }

    }
    }


    public function errorAction(){


    }

}


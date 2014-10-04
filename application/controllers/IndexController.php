<?php

class IndexController extends My_Controller_Action
{


    public function indexAction()
    {


        $this->view->assign("logo","Login please!");

        $auth = Zend_Auth::getInstance();

        if( $auth->hasIdentity() ){

            $usertable=new Table_User();
            $userauth=$auth->getIdentity();
            $username=$userauth["username"];

            $user=$usertable->getByUsername($username);


            if (($user->getActivationCode())!="")
            {

                $this->redirect("/auth/sendnotify-registration?username=".$username);

            }
            else
            {
            if ($userauth["role"]==Table_User::ROLE_USER)
                $this->redirect("/any-user");
            if ($userauth["role"]==Table_User::ROLE_ADMIN)
                $this->redirect("/user");
            }

    }
    }


    public function errorAction(){


    }

}


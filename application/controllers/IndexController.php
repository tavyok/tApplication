<?php

class IndexController extends My_Controller_Action
{


    public function indexAction()
    {
        // action body

        $this->view->assign("logo","Login please!");

        $auth = Zend_Auth::getInstance();

        if( $auth->hasIdentity() ){
/*
            $usertable=new Table_User();
            $username=$auth->getIdentity()["username"];
            My_Log_Me::Log( $username);
            $user=$usertable->getByUsername($username);
            if (! is_null($user->getActivationCode()))
            {
                echo "<H3>Registration unfinished! <BR>A new email was sent to your email address containing a registration confirmation.<BR> Please check your email to activate your account!</H3>";

                $mail=new My_HtmlMailer();
                $mail->sendActivationCode($user->getUsername());
            }
            else*/
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


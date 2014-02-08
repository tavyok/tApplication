<?php

class IndexController extends My_Controller_Action
{


    public function indexAction()
    {
        // action body

        $this->view->assign("name","Login please!");

        $auth = Zend_Auth::getInstance();

        if( $auth->hasIdentity() ){
            $this->redirect("/user");
        }
        else
           $this->redirect("/auth");

    }

    public function mainAction()
    {
        // action body

        $this->view->assign("name","tavi");
    }

    public function menuAction()
    {
        // action body

        $this->view->assign("name","menu index tavi");
    }




}


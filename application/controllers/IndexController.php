<?php

class IndexController extends My_Controller_Action
{


    public function indexAction()
    {
        // action body

        $this->view->assign("name","index tavi");
        $this->redirect("../user");
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


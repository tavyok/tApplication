<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {
        // action body

        $this->view->assign("name","index tavi");
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


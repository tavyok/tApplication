<?php
/**
 * Created by PhpStorm.
 * User: Tavy
 * Date: 22.03.2014
 * Time: 03:35
 */

class AnyUserController extends My_Controller_Action
{

    public function init()
    {

        parent::init();

        // $this->_helper->_layout->setLayout('layout-orig');
        $auth = Zend_Auth::getInstance();
        $name = $auth->getIdentity()["first_name"]." ".$auth->getIdentity()["last_name"];

        $this->view->assign("action", $this->getRequest()->getActionName());
        $this->view->assign("name", $name);
//        $this->view->render('any-user/_menu.phtml');

    }


   public function indexAction()
   {




   }

   public function profilAction()
   {




    }



}

?>
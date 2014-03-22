<?php
/**
 * Created by PhpStorm.
 * User: Tavy
 * Date: 22.03.2014
 * Time: 03:35
 */

class AnyuserController extends My_Controller_Action
{

    public function init()
    {

        parent::init();

        // $this->_helper->_layout->setLayout('layout-orig');

        $this->view->assign("action", $this->getRequest()->getActionName());
        $this->view->render('anyuser/_menu.phtml');
        $auth = Zend_Auth::getInstance();
        $name=$auth->getIdentity()["first_name"]." ".$auth->getIdentity()["last_name"];
  //      $this->view->assign("name",$name);
        $this->view->name=$name;

    }


   public function indexAction()
   {




   }

   public function profilAction()
   {




    }



}

?>
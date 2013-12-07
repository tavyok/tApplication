<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 11/23/13
 * Time: 3:07 PM
 */

class UserController extends Zend_Controller_Action {


    public function init(){
        $this->_helper->_layout->setLayout('layout-page');

        $this->view->assign("action",$this->getRequest()->getActionName());
        $this->view->render('user/_menu.phtml');
    }

    public function indexAction(){
    }



    public function addAction(){

        if( $this->getRequest()->isPost()){

            $params = $this->getRequest()->getParams();

            Zend_Debug::dump($params["email"]);
            Zend_Debug::dump($params["password"]);

            $rememberMe = $this->getRequest()->getParam("rememberMe",0);
            Zend_Debug::dump($rememberMe);

            // adaugare

            $this->redirect("/user");
        }


        $utils = new My_Utils("sese");

        $this->view->assign("utils",$utils);
    }


    public function editAction(){

    }


    public function deleteAction(){

    }
} 
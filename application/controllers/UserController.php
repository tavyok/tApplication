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
        $tableUser = new Table_User();

        if( $this->getRequest()->isPost() ){
            Zend_Debug::dump($this->getRequest()->getParams());

            $cb = $this->getRequest()->getParam("cb",array());

            /*
            if( ! empty( $cb ) ){
                for( $i = 0; $i < count( $cb ) ; $i++ ){
                    $where = $tableUser->getAdapter()->quoteInto("id = ?", $cb[$i]);
                    $tableUser->delete($where);
                }
            }
            */

/*
            if( ! empty( $cb ) ){
                $where = $tableUser->getAdapter()->quoteInto("id in (?)", $cb);
                $tableUser->delete($where);
            }
*/
            if( ! empty( $cb ) ){
                $tableUser->deleteByIds($cb);
            }

        }

        $users = $tableUser->getAll();

        $this->view->assign("users",$users);

    }



    public function addAction(){



        if( $this->getRequest()->isPost()){

            $params = $this->getRequest()->getParams();

            $tableUser = new Table_User();

            /** @var Model_User $user */
            $user = $tableUser->createRow($params);

            $password = $params["password"];
            $user->setPass(md5($password));

            try{
                $user->save();
            }
            catch( Exception $e ){
                Zend_Debug::dump($e->getMessage());
                return;
            }


            $this->redirect("/user");
        }


        $utils = new My_Utils("sese");

        $utils->email = 'nnsese@gmail.com';

        Zend_Debug::dump($utils->email);

        $this->view->assign("utils",$utils);
    }


    public function editAction(){

    }


    public function deleteAction(){

    }
} 
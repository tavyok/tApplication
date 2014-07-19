<?php

/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 11/23/13
 * Time: 3:07 PM
 */
class UserController extends My_Controller_Action
{


    public function init()
    {

        parent::init();

       //  $this->_helper->_layout->setLayout('layout-orig');

        $this->view->assign("action", $this->getRequest()->getActionName());
        $this->view->render('user/_menu.phtml'); //include _menu.phtml in pagina
        $this->view->render('user/_signin.phtml');


    }

    public function indexAction()
    {
        $tableUser = new Table_User();

        if ($this->getRequest()->isPost()) {

            $cb = $this->getRequest()->getParam("cb", array());

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
            if (!empty($cb)) {
                $tableUser->deleteByIds($cb);
            }

        }

        $users = $tableUser->getAll();

//        $this->view->assign("users",$users); or
        $this->view->users = $users;
    }


    public function addAction()
    {

        if ($this->getRequest()->isPost()) {

            $params = $this->getRequest()->getParams();

            if (isset($params["adduser"])) {
                $tableUser = new Table_User();

                /** @var Model_User $user */
                $user = $tableUser->createRow($params);

                $password = $params["password"];
                $user->setPassword(md5($password));
                try {
                    $user->save();
                } catch (Exception $e) {
                    Zend_Debug::dump($e->getMessage());
                    return;
                }

               require_once("/newphotosave.php");

               $this->view->assign("emailto",$user->getEmail());
               $this->redirect("/auth/signup-notify?username=".$user->getUsername()."&emailto=".$user->getEmail()."&goto=/user/index");


            }

            $this->redirect("/user");
        }
    }

    public function editAction()
    {
        My_Utils::cleanAvatars();
        if (is_null($id = $this->getRequest()->getParam("id"))) {
            throw new Exception("Missing User ID !", 501);
        }

        $tableUser = new Table_User();
        if (is_null($user = $tableUser->getById($id))) {
            throw new Exception("Missing User for ID !", 501);
        }
        $this->view->assign("user", $user);
        if ($this->getRequest()->isPost()) {

            $params = $this->getRequest()->getParams();

            if( trim( $params['password'] ) ){
                $params['password'] =  md5(trim($params["password"]));
            }
            else{
                unset($params['password']);
            }

            $role_ini=$user->getRole();

            $user->setFromArray($params);

            try {

                $user->save();

            }
            catch (Exception $e) {
                Zend_Debug::dump($e->getTraceAsString());
                return;
            }
            $user=$tableUser->getByUsername($params["username"]);
            require_once"/editphotosave.php";

            if ($this->identity["username"]==$user->getUsername())
                if ($role_ini!=$params["role"])
                {
                    Zend_Auth::getInstance()->clearIdentity();
                     setcookie("_tAppCookie", '', time(), '/');
                     $this->redirect("/auth/silent?username=".$user->getEmail()."&password=".$user->getPassword());
                }
            $this->redirect("/user");
    }
    }

    public function photosAction(){

        //   $this->disableLayout()->disableView();

        $this->redirect("/upload/photos");

    }

    public function testAction(){
        die("Test Action");
    }
} 
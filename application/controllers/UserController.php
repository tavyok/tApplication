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

        // $this->_helper->_layout->setLayout('layout-orig');

        $this->view->assign("action", $this->getRequest()->getActionName());
        $this->view->render('user/_menu.phtml'); //include _menu.phtml in pagina
        $this->view->render('user/_signin.phtml');

        if (isset($_COOKIE['user_signed'])) {
            var_dump($_COOKIE['user_signed']);
        }

    }

    public function indexAction()
    {
        $tableUser = new Table_User();

        if ($this->getRequest()->isPost()) {
            Zend_Debug::dump($this->getRequest()->getParams());

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
        /*        $utils = new My_Utils("sese");

                $utils->email = 'nnsese@gmail.com';
                $utils->name="salajan";
                $utils->setName("tavy");
                Zend_Debug::dump($utils);


                $this->view->assign("utils",$utils);*/

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
                $user->setPass(md5($password));

                try {
                    $user->save();
                } catch (Exception $e) {
                    Zend_Debug::dump($e->getMessage());
                    return;
                }

            }

            $this->redirect("/user");
        }
    }

    public function editAction()
    {

        if (is_null($id = $this->getRequest()->getParam("id"))) {
            throw new Exception("Missing User ID !", 501);
        }

        $userTable = new Table_User();
        if (is_null($user = $userTable->getById($id))) {
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

            $user->setFromArray($params);

            try {
                My_Log_Me::Log($user->toArray());
                $user->save();
            }
            catch (Exception $e) {
                Zend_Debug::dump($e->getTraceAsString());
                return;
            }

            $this->redirect("/user");
        }

    }

    public function checkUsernameAction()
    {

        $id = $this->getRequest()->getParam("id");
        $username = $this->getRequest()->getParam("username");

        $userTable = new Table_User();

        if ( ! is_null($user = $userTable->getByUsername($username))) {
            if( is_null( $id ) &&  $id != $user->getId() ){
                $this->sendJson("User already exists");
            }
        }

        // user do not exists with this username !
        $this->sendJson(true);
    }

    public function checkEmailAction()
    {
        $id = $this->getRequest()->getParam("id");
        $email = $this->getRequest()->getParam("email");

        $userTable = new Table_User();
        if (!is_null($user = $userTable->getByEmail($email))) {
            if( is_null( $id ) &&  $id != $user->getId() ){
                $this->sendJson("Email already taken");
            }
        }

        // user do not exists with this email !
        $this->sendJson(true);
    }

} 
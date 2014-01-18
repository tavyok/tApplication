<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 11/23/13
 * Time: 3:07 PM
 */

class UserController extends My_Controller_Action {


    public function init(){

        parent::init();

        $this->view->assign("action",$this->getRequest()->getActionName());
        $this->view->render('user/_menu.phtml');  //include _menu.phtml in pagina
        $this->view->render('user/_signin.phtml');

        if( isset( $_COOKIE['user_signed']  ) )
        {
            var_dump($_COOKIE );
        }


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

            if (isset($params["adduser"]))
            {
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

            }



        $utils = new My_Utils("sese");

        $utils->email = 'nnsese@gmail.com';

        Zend_Debug::dump($utils->email);


        $this->view->assign("utils",$utils);


        if ((isset($params["signed_email"])) and (isset($params["signed_email"])))
        {

            $cookie = new Zend_Http_Header_SetCookie();
            $cookie->setName("user_signed")
                    ->setValue($params["signed_email"] . md5($params["signed_password"]))
                    ->setDomain('tavy.tapp')
                    ->setPath('/')
                    ->setHttponly(true)
                    ->setMaxAge(86400);

           $this->getResponse()->setRawHeader($cookie);
        }
       $this->redirect("/user");
        }
    }

    public function editAction(){

        if( is_null( $id = $this->getRequest()->getParam("id") ) ){
            throw new Exception("Missing User ID !", 501);
        }

        $userTable = new Table_User();
        if( is_null( $user = $userTable->getById( $id ) )){
            throw new Exception("Missing User for ID !", 501);
        }
        Zend_Debug::dump($user->toArray());

        $this->view->assign("user", $user);
    }

    public function checkEmailAction(){
        $email = $this->getRequest()->getParam("email");

        $this->disableLayout()->disableView();
        /** @var Zend_Controller_Action_Helper_Json $jsonHelper */
        $jsonHelper = $this->_helper->json;

        $userTable = new Table_User();
        if( ! is_null( $user = $userTable->getByEmail($email) )) {
            $jsonHelper->sendJson("User Already Exists");
            return;
        }

        // user do not exists with this email !
        $jsonHelper->sendJson(true);
    }
} 
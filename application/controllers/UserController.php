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

       // $this->_helper->_layout->setLayout('layout-orig');

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
/*        $utils = new My_Utils("sese");

        $utils->email = 'nnsese@gmail.com';
        $utils->name="salajan";
        $utils->setName("tavy");
        Zend_Debug::dump($utils);


        $this->view->assign("utils",$utils);*/

        $users = $tableUser->getAll();

//        $this->view->assign("users",$users); or
        $this->view->users=$users;
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
        if( is_null( $user = $userTable->getById($id) )){
            throw new Exception("Missing User for ID !", 501);
        }


        $this->view->assign("user", $user);

        if( $this->getRequest()->isPost()){

        $params = $this->getRequest()->getParams();
        if (isset($params["edituser"]))
        {
            $password = md5($params["password"]);
            $user->setAll($params["username"],$params["email"],$password,$params["first_name"],$params["last_name"],$params["phone"]);

            $where=$userTable->getAdapter()->quoteInto("id=?",$id);

          //  Zend_Debug::dump($user->toArray());

            try{
                $userTable->update($user->toArray(),$where);
            }
            catch( Exception $e ){
                Zend_Debug::dump($e->getMessage());
                return;
            }

        }
            $this->redirect("/user");
        }

    }

    public function checkUsernameAction(){
        $username = $this->getRequest()->getParam("username");

     //   $this->disableLayout()->disableView();
        /** @var Zend_Controller_Action_Helper_Json $jsonHelper */
        $jsonHelper = $this->_helper->json;

        $userTable = new Table_User();
        if( ! is_null( $user = $userTable->getByUsername($username) )) {
            $jsonHelper->sendJson("User already exists");
            return;
        }

        // user do not exists with this username !
        $jsonHelper->sendJson(true);
    }

    public function checkEmailAction(){
        $email = $this->getRequest()->getParam("email");

    //    $this->disableLayout()->disableView();
        /** @var Zend_Controller_Action_Helper_Json $jsonHelper */
        $jsonHelper = $this->_helper->json;

        $userTable = new Table_User();
        if( ! is_null( $user = $userTable->getByEmail($email) )) {
            $jsonHelper->sendJson("Email already taken");
            return;
        }

        // user do not exists with this email !
        $jsonHelper->sendJson(true);
    }

    public function checkEdtusernameAction(){
        $username = $this->getRequest()->getParam("username");
        $username_ini = $this->getRequest()->getParam("username_ini");
        //   $this->disableLayout()->disableView();
        /** @var Zend_Controller_Action_Helper_Json $jsonHelper */
        $jsonHelper = $this->_helper->json;

        $userTable = new Table_User();

        if ((! is_null( $user = $userTable->getByUsername($username) )) and ($username!=$username_ini))
         {

            $jsonHelper->sendJson("User already exists");
         }

        // user do not exists with this username !

             $jsonHelper->sendJson(true);
}


    public function checkEdtemailAction(){
        $email = $this->getRequest()->getParam("email");
        $email_ini = $this->getRequest()->getParam("email_ini");
      //  $this->disableLayout()->disableView();
        /** @var Zend_Controller_Action_Helper_Json $jsonHelper */
        $jsonHelper = $this->_helper->json;

        $userTable = new Table_User();
        if ((! is_null( $user = $userTable->getByEmail($email) )) and ($email!=$email_ini))
        {
            $jsonHelper->sendJson("Email already taken");
            return;
        }

        // user do not exists with this email !
        $jsonHelper->sendJson(true);
    }

} 
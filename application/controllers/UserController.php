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

                $user=$tableUser->getByUsername($params["username"]);

                if( isset ($params["photoup"] ) ){
                    $photo = $params["photoup"];

                    $uploadFolder = realpath( $this->config['upload']['folder'] );
                    if ($photo!="")
                    if( file_exists( $uploadFolder."/".$photo)){
                       $ext  = strtolower( pathinfo($photo,PATHINFO_EXTENSION) );
                       $newFileName = My_Utils::buildImageFile( $user->getId() ) . "." . $ext;


                        if(  rename($uploadFolder. "/" .$photo, $uploadFolder  . '/' .  $newFileName )){
                            My_Log_Me::Log( "moved". $photo ."->".$newFileName);

                            }


                    $user->setPhoto($newFileName);
                    try {
                        $user->save();
                    } catch (Exception $e) {
                        Zend_Debug::dump($e->getMessage());
                        return;
                    }
                    }
                }
                $this->view->assign("emailto",$user->getEmail());
                $this->redirect("/auth/signup-notify?username=".$user->getUsername()."&emailto=".$user->getEmail()."&goto=/user/index");


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

                $user->save();

            }
            catch (Exception $e) {
                Zend_Debug::dump($e->getTraceAsString());
                return;
            }

            $user=$userTable->getByUsername($params["username"]);

            if( isset ($params["photoup"] ) )
            {

                $photo = $params["photoup"];
                My_Log_Me::Log("extract ".$photo);
                $uploadFolder = realpath( $this->config['upload']['folder'] );
                if ($photo!="")
                {
                    if( file_exists( $uploadFolder."/".$photo)){
                        $ext  = strtolower( pathinfo($photo,PATHINFO_EXTENSION) );
                        $newFileName = My_Utils::buildImageFile( $user->getId() ) . "." . $ext;


                        if(  rename($uploadFolder. "/" .$photo, $uploadFolder  . '/' .  $newFileName )){
                            My_Log_Me::Log( "moved ". $photo ."->".$newFileName);

                        }


                        $user->setPhoto($newFileName);
                        try {
                            $user->save();
                        } catch (Exception $e) {
                            Zend_Debug::dump($e->getMessage());
                            return;
                        }
                    }
                }

                else
                {

                    $photo=$user->getPhoto();
                    if( file_exists( $uploadFolder."/".$photo)){
                        $ext  = strtolower( pathinfo($photo,PATHINFO_EXTENSION) );
                        $filetoremove= My_Utils::buildImageFile( $user->getId() ) . "." . $ext;
                        unlink($uploadFolder."/".$filetoremove);
                        $user->setPhoto("");
                        try {
                            $user->save();
                        }
                        catch (Exception $e) {
                            Zend_Debug::dump($e->getMessage());
                            return;

                        }
                    }
                }
                 $this->redirect("/user");
        }

    }
    }



    public function testAction(){
        die("Test Action");
    }
} 
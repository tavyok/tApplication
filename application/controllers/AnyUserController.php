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
        $user= $auth->getIdentity()["username"];
        $id= $auth->getIdentity()["id"];
        $this->view->assign("action", $this->getRequest()->getActionName());
        $this->view->assign("name", $name);
        $this->view->assign("user", $user);
        $this->view->assign("id", $id);
    }


   public function indexAction()
   {


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
            $this->redirect("/any-user");
        }

    }



}
}

?>
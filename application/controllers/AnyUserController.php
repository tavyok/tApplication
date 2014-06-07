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
    }


    public function indexAction()
    {


    }

    public function editAction()
    {

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

            if (trim($params['password'])) {
                $params['password'] = md5(trim($params["password"]));
            } else {
                unset($params['password']);
            }

            $user->setFromArray($params);

            try {

                $user->save();
            } catch (Exception $e) {
                Zend_Debug::dump($e->getTraceAsString());
                return;
            }
            require_once("/editphotosave.php");
            $this->redirect("/any-user");
        }
    }

    public function photosAction(){

    }
}


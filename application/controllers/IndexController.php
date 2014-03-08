<?php

class IndexController extends My_Controller_Action
{


    public function indexAction()
    {
        // action body

        $this->view->assign("name","Login please!");

        $auth = Zend_Auth::getInstance();

        if( $auth->hasIdentity() ){
            $this->redirect("/user");
        }

    }

    public function signupAction()
    {
       // Zend_Debug::dump(Zend_Auth::getInstance()->getIdentity()["role"]);
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

            }

      //      $this->redirect("/user");
        }


    }

    public function menuAction()
    {
        // action body

        $this->view->assign("name","menu index tavi");
    }


    public function errorAction(){


    }

}


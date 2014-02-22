<?php

class SandboxController extends My_Controller_Action
{

    public function indexAction()
    {
        $this->disableLayout()->disableView();


        $acl = My_Acl::getInstance();

        $module = 'default';
        $controller = 'index';
        $action = 'main';

        $resource = $module . ':' . $controller;

        if( $acl->has($resource) ){
            Zend_Debug::dump($acl->isAllowed( Table_User::ROLE_ADMIN, $resource, $action ));
        }


    }

}


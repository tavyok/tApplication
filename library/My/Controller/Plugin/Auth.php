<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 2/8/14
 * Time: 4:55 PM
 */



class My_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract{


    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {

        $auth = Zend_Auth::getInstance();

    //    Zend_Debug::dump($this->getRequest()->getParams());

        $dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();

      //  My_Log_Me::Log( $request->getActionName());

        // Check controller
/*
            if ($request->getControllerName()=="index")
                if ( ! $dispatcher->isDispatchable($request))
              $request->setActionName("index");*/

       if ( ! $dispatcher->isDispatchable($request))
        {
            $request->setModuleName('default')
                ->setControllerName('index')
                ->setActionName('error');

            return;
        }


        if( ! $auth->hasIdentity() ){
            $role = Table_User::ROLE_GUEST;
        }
        else{

            $role = $auth->getInstance()->getIdentity()["role"];
            /*$roles = $auth->getInstance()->getIdentity();
            $role=$roles['role'];*/
        }

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $resource = $module . ':' . $controller;

        $acl = My_Acl::getInstance();

        if( ! $acl->isAllowed( $role, $resource, $action ) ){
            $request->setModuleName('default')
                ->setControllerName('auth')
                ->setActionName('index');
        }

    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {


        if ( isset($_COOKIE["_tAppCookie"]) ) {

            $emailAuth = $_COOKIE["_tAppCookie"];

            $userTable = new Table_User();

            if( is_null( $user = $userTable->getByEmail($emailAuth) ) ) {
                setcookie("_tAppCookie", '', time(),'/');
                return;
            }

            $myAdapter = new My_Adapter();
            $myAdapter->setUser($user);

            Zend_Auth::getInstance()->authenticate($myAdapter);

        }
    }

} 
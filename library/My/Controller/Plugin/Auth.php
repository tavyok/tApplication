<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 2/8/14
 * Time: 4:55 PM
 */



class My_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract{


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

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {

        $auth = Zend_Auth::getInstance();

        if(! $auth->hasIdentity() ){
            $request->setControllerName('index')
                     ->setActionName('index');

        }
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $auth = Zend_Auth::getInstance();
        if(! $auth->hasIdentity() ){


           $front = Zend_Controller_Front::getInstance();

/*            $response = new Zend_Controller_Request_Http();
            $response->getBasePath();
            $response->setRequestUri("/");
        */
        }
    }


} 
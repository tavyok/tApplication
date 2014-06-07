<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 1/18/14
 * Time: 4:30 PM
 */

class My_Controller_Action extends Zend_Controller_Action {

    protected $config;
    protected $identity;

    public function init(){
        $this->config = Zend_Registry::get('__CONFIG__');

        if( Zend_Auth::getInstance()->hasIdentity() ){
            $this->identity = Zend_Auth::getInstance()->getIdentity();
        }

        $this->view->assign("identity",$this->identity);
        $this->view->assign("action", $this->getRequest()->getActionName());
    }


    public function preDispatch(){

        $dispatcher = $dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();

        $actionName = $dispatcher->formatActionName($this->getRequest()->getActionName());

        if(  ! in_array($actionName, get_class_methods($this) ) ){
            $this->redirect("auth");
        }
    }


    public function disableLayout(){
        $this->_helper->layout->disableLayout();
        return $this;
    }

    public function disableView(){
        $this->_helper->viewRenderer->setNoRender(true);
        return $this;
    }


    /**
     * Send params as json object
     * @param $params
     */
    public function sendJson($params){
        $this->disableLayout()->disableView();
        /** @var Zend_Controller_Action_Helper_Json $jsonHelper */
        $jsonHelper = $this->_helper->json;

        $jsonHelper->sendJson($params);
    }

} 
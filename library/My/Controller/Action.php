<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 1/18/14
 * Time: 4:30 PM
 */

class My_Controller_Action extends Zend_Controller_Action {


    public function init(){

    }

    public function disableLayout(){
        $this->_helper->layout->disableLayout();
        return $this;
    }

    public function disableView(){
        $this->_helper->viewRenderer->setNoRender(true);
        return $this;
    }


} 
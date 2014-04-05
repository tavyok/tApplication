<?php

class SandboxController extends My_Controller_Action
{

    public function indexAction()
    {
        $this->disableLayout()->disableView();


//        Zend_Debug::dump( date_default_timezone_get() );


        $htmlMailer = new My_HtmlMailer();

        $htmlMailer->sendActivationCode("tavy");


    }

}


<?php

class SandboxController extends My_Controller_Action
{

    public function indexAction()
    {

        $this->disableLayout()->disableView();


        $filterChain = new Zend_Filter();

        $filterChain->addFilter(new Zend_Filter_Word_CamelCaseToDash());
        $filterChain->addFilter(new Zend_Filter_StringToLower());
        $filterChain->addFilter(new Zend_Filter_Word_DashToCamelCase());

        $text = "AcestaEsteUnText";

     //   Zend_Debug::dump($filterChain->filter( $text ) );






 /*       $today = getdate();
        $today = date("HidmY");
        print_r($today);
        echo '<BR>'. strtotime($today);
        $datetime = new DateTime();
        echo $datetime->format('HidmY');
        echo "<BR>". $datetime->getTimestamp();

        $datetime2 = new DateTime();

       $datetime2->modify('+1 year 2 hour 1 minute');
        echo "<BR>".$datetime2->format('HidmY');
        $interval = $datetime->diff($datetime2);
        echo $interval->format('%R%h Hours %i Minute %s Seconds');
        echo $interval->h;*/


/*        $id = array( "id" => 120 );
        $criptat = My_Crypt::Encrypt( $id );

        Zend_Debug::dump($criptat);

        Zend_Debug::dump( My_Crypt::Decrypt( $criptat ));

        $decriptat = My_Crypt::Decrypt( $criptat );

        $dt = Date("Y-m-d H:i:s",$decriptat["expire"]);

        Zend_Debug::dump($dt);


        $dt = new Datetime();
        $dt = $dt->setTimestamp($decriptat["expire"]);

        Zend_Debug::dump($dt->format("Y-m-d H:i:s"));
*/
    }

}


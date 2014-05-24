<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 3/22/14
 * Time: 4:46 PM
 */

class My_HtmlMailer extends Zend_Mail {


    const VIEW_SCRIPT_PATH = "/views/";

    const TPL_ACTIVATEUSER_PATH = "_emails/activate-user.phtml";


    protected $_sendMail = 1;
    protected $_logMail = 0;

    protected $_zendView;

    protected $_domain;


    public function __construct(){
        parent::__construct();

        $config = Zend_Registry::get("__CONFIG__");

        if( isset( $config["mail"]) ){
            if( array_key_exists("sendMail",$config["mail"])){
                $this->_sendMail = $config["mail"]["sendMail"];
            }
            if( array_key_exists("logMail",$config["mail"])){
                $this->_logMail = $config["mail"]["logMail"];
            }
        }

        $zendView = new Zend_View();
        $zendView->setBasePath( APPLICATION_PATH . self::VIEW_SCRIPT_PATH);

        $this->_zendView = $zendView;

        $this->_domain = "http://" . $_SERVER['SERVER_NAME'];
    }


    /**
     * Wrapper for zend view assign
     *
     * @param $spec
     * @param null $value
     */
    public function assign($spec, $value = null){
        $this->_zendView->assign($spec, $value);
    }


    /**
     * Zend Mail & render template & log mail
     *
     * @param $template_path
     * @return bool|Zend_Mail
     */


    public function sendMail( $template_path,$emailto,$subject ){

        $this->assign("domain",$this->_domain);
        $this->addTo($emailto);
        $this->setSubject($subject);

        $user=new Table_User();

        $this->assign("fullname",$user->getByEmail($emailto)->getFullName());
        $this->assign("username",$user->getByEmail($emailto)->getUsername());
        $this->assign("activation_code",$user->getByEmail($emailto)->getActivationCode());
        $htmlContent = $this->_zendView->render($template_path);


        $this->setBodyHtml( $htmlContent );

        if( $this->_logMail ){
            My_Log_Me::Log( $htmlContent );
        }

        if( $this->_sendMail ){
            return $this->send();
        }

        return false;
    }

    /**
     * Send Notification
     *
     * @param $username
     * @internal param $email
     * @return bool|Zend_Mail
     */
    public function sendActivationCode($username){

        $subject = "Activate your account on T-App";
        $userTable = new Table_User();
        $user=$userTable->getByUsername($username);
        try
        {
            $emailTo = $user->getEmail();

            $code = My_Utils::randomstr(10);
            $user->setActivationCode($code);
            $user->save();

            return $this->sendMail( self::TPL_ACTIVATEUSER_PATH,$emailTo,$subject );

        }
        catch (Exception $e) {
            My_Log_Me::Log($e->getMessage());
        }
        return false;
    }
} 
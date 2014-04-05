<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 2/1/14
 * Time: 5:51 PM
 */

class My_Adapter implements Zend_Auth_Adapter_Interface {

    protected $email;
    protected $password;

    /** @var  Model_User */
    protected $user;

    public function setEmailPassword($email, $password){
        $this->email = $email;
        $this->password = $password;
    }

    public function setUser( Model_User $user ){
        $this->user = $user;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        // user object was sent from controller plugin
        if( ! is_null( $this->user ) ){
            return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$this->user->toArray());
        }

        // email/pass was sent from login
        $userTable = new Table_User();
        $user = $userTable->authenticate($this->email, $this->password);

        if( is_null( $user ) ){
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,null,array('User not found'));
        }

        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$user->toArray());


    }
}
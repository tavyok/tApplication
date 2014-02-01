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


    public function setEmailPassword($email, $password){
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        // TODO: Implement authenticate() method.

        $userTable = new Table_User();

        $user = $userTable->authenticate($this->email, $this->password);

        if( is_null( $user ) ){
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,null,array('User not found'));
        }


        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$user->toArray());
    }
}
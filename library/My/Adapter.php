<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 2/1/14
 * Time: 5:51 PM
 */

class My_Adapter implements Zend_Auth_Adapter_Interface {

    protected $userId;
    protected $email;
    protected $password;

    protected $username;
    protected $activationCode;


    /** @var  Model_User */
    protected $user;

    public function setEmailPassword($email, $password){
        $this->email = $email;
        $this->password = $password;
    }

    public function setUser( Model_User $user ){
        $this->user = $user;
    }

    public function setUserId( $userId  ){
        $this->userId = $userId;
    }

    public function setUsernameActivationCode( $username, $activationCode ){
        $this->username = $username;
        $this->activationCode = $activationCode;
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

        $userTable = new Table_User();

        if( $this->userId ){
            $user = $userTable->getById( $this->userId );
        }
        elseif( $this->username && $this->activationCode ){
            $user = $userTable->getByUsernameActivationCode($this->username, $this->activationCode);
        }
        else{
            My_Log_Me::Log($this->email.$this->password);
            // email/pass was sent from login
            $user = $userTable->getByEmailPassword($this->email, $this->password);

            if( is_null( $user ) ){
                $user = $userTable->getByUsernamePassword($this->email, $this->password);
            }
        }

        if( is_null( $user ) ){
            return new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,null,array('User not found'));
        }

        return new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$user->toArray());


    }
}
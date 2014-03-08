<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 2/22/14
 * Time: 6:01 PM
 */

class My_Acl extends Zend_Acl {

    
    static protected $_instance;
    
    protected function __construct(){

        $resDefaultAuth    = new Zend_Acl_Resource( 'default:auth' );
        $resDefaultError   = new Zend_Acl_Resource( 'default:error' );
        $resDefaultIndex   = new Zend_Acl_Resource( 'default:index' );
        $resDefaultUser    = new Zend_Acl_Resource( 'default:user' );
        $resDefaultSandbox = new Zend_Acl_Resource( 'default:sandbox' );

        // admin
        $resAdminIndex   = new Zend_Acl_Resource( 'admin:index' );
        $resAdminManager    = new Zend_Acl_Resource( 'admin:manager' );

        $this->addResource( $resDefaultAuth );
        $this->addResource( $resDefaultError );
        $this->addResource( $resDefaultIndex );
        $this->addResource( $resDefaultUser );
        $this->addResource( $resDefaultSandbox );


        $this->addResource( $resAdminIndex );
        $this->addResource( $resAdminManager );


        $roleGuest = Table_User::ROLE_GUEST;
        $roleUser  = Table_User::ROLE_USER;
        $roleAdmin = Table_User::ROLE_ADMIN;

        $this->addRole( $roleGuest );
        $this->addRole( $roleUser, $roleGuest );
        $this->addRole( $roleAdmin, $roleUser );


        // allow Guest
        $this->allow( $roleGuest, $resDefaultError );
        $this->allow( $roleGuest, $resDefaultIndex );
        $this->allow( $roleGuest, $resDefaultAuth );
        $this->allow( $roleGuest, $resDefaultUser , array('check-username','check-email') );
    //    $this->allow( $roleGuest, $resDefaultUser , array('test') );

        // allow User
        $this->allow( $roleUser, $resDefaultUser );
        $this->allow( $roleUser, $resDefaultSandbox );

        $this->allow( $roleAdmin );
                
    }
    
    static public function getInstance(){
    
        if( is_null( self::$_instance ) ){
            self::$_instance = new self;
        } 
        
        return self::$_instance;
    }
    
} 
<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 12/7/13
 * Time: 3:05 PM
 */

class My_Utils {


    static protected $_instance;

    public function __construct(){

        //self::getInstance();
    }

    static public function getInstance(){
        if( is_null( self::$_instance) ){
            //      print "Instanta Noua !\n";
            self::$_instance = new My_Utils();
        }
        return self::$_instance;

    }

    public static function randomstr($n)
    {

        $s="";
        for ($i=1;$i<=$n;$i++)
        {
            $s=$s.chr(rand(65,90));

        }
        return $s;

    }
}
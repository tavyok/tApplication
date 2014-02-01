<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hpsese
 * Date: 21/09/11
 * Time: 13:03
 * To change this template use File | Settings | File Templates.
 */
 
class My_Log_Firebug {

    /** @var $logger Zend_Log */
    private static $logger;

    /**
     * @static
     * @param $message
     * @param int $priority
     * @param null $extras
     * @return void
     */
    static public function Log($message, $priority=Zend_Log::INFO, $extras = null){
        if( is_null(self::$logger) ){
            self::$logger = new Zend_Log(new Zend_Log_Writer_Firebug());
        }

        self::$logger->log($message,$priority,$extras);
    }
}

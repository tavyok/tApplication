<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hpsese
 * Date: 21/09/11
 * Time: 13:03
 * To change this template use File | Settings | File Templates.
 */
 
class My_Log_Me {

    /** @var $logger Zend_Log */
    private static $logger;

    // relative to APPLICATION_PATH
    private static $logFile = "logme.log";
    private static $logFolder;

    /**
     * @static
     * @param $message
     * @param int $priority
     * @param null $extras
     * @throws Exception
     * @return void
     */
    static public function Log($message, $priority=Zend_Log::INFO, $extras = null){

        if( is_null(self::$logger) ){

            if( is_null( self::$logFolder ) ){
                $absLogPath = realpath( APPLICATION_PATH . "/../public" );
            }
            else{
                $absLogPath = realpath( self::$logFolder );
            }

            if( $absLogPath === false ){
                throw new Exception("Invalid Folder to log:: ".self::$logFolder);
            }

            $logFile = $absLogPath  . "/" .  ltrim(self::$logFile,"/");

            if( ! is_writeable($logFile) ){
                throw new Exception("Folder is not writable:: ".$absLogPath);
            }


            self::$logger = new Zend_Log(new Zend_Log_Writer_Stream($logFile));
            self::$logger->setTimestampFormat("Y-m-d H:m");
        }

        ob_start();
        $trace = debug_backtrace(false);

        $caller = $trace[1];
        if ($caller["function"])
        {
            print sprintf("Message from: %s->%s\n", $caller["class"], $caller["function"]);
        }
        var_dump($message);

        /** id the second parameter is exception write the trace */
        if ($priority instanceof Exception)
        {
            /*             * @var $priority Exception */
            print $priority->getMessage() . "\n" . $priority->getTraceAsString();
        }

        $output = ob_get_clean();
        // neaten the newlines and indents
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);

        self::$logger->log($output, $priority, $extras);    }

    static public function setLogFile($logFile){
        self::$logFile = $logFile;
    }

    static public function setLogFolder($logFolder){
        self::$logFolder = $logFolder;
    }

}

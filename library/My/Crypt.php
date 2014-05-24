<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hpsese
 * Date: 22/09/11
 * Time: 12:28
 * To change this template use File | Settings | File Templates.
 */

class My_Crypt
{

    static private $key = "You have to think anyway, so why not think big?";

    static private $vector = "Donald T";

    static private $expire = "6";

    public static function  Decrypt($urlEncB64encrypted)
    {
        $config = Zend_Registry::get("__CONFIG__");

        $key = isset($config['mcrypt']['key']) ? $config['mcrypt']['key'] : self::$key;
        $vector = isset($config['mcrypt']['vector']) ? $config['mcrypt']['vector'] : self::$vector;

        $configMCrypt = array('adapter' => 'mcrypt',
                              'key' => $key,
                              'vector' => $vector);

        $filter = new Zend_Filter_Decrypt($configMCrypt);

        $encrypted = base64_decode(strtr($urlEncB64encrypted, '-_,', '+/='));

        $json = $filter->filter($encrypted);

        return Zend_Json::decode(trim($json));
    }


    public static function Encrypt($params)
    {
        $config = Zend_Registry::get("__CONFIG__");

        $key = isset($config['mcrypt']['key']) ? $config['mcrypt']['key'] : self::$key;
        $vector = isset($config['mcrypt']['vector']) ? $config['mcrypt']['vector'] : self::$vector;
        $expire = isset($config['mcrypt']['expire']) ? $config['mcrypt']['expire'] : self::$expire;

        $configMCrypt = array('adapter' => 'mcrypt',
                              'key' => $key,
                              'vector' => $vector);

        if ( is_scalar($params) ) {
            $params = array("param" => $params);
        }

        if ( ! array_key_exists("expire", $params)) {
            $nextTime = mktime(Date('H') + $expire, Date('i'), 0, Date('m'), Date('d'), Date('Y'));
            $params["expire"] = $nextTime;
        }

        /** @var $filter Zend_Filter_Encrypt */
        $filter = new Zend_Filter_Encrypt($configMCrypt);

        /** json is smaller than serialize  */
        $json = Zend_Json::encode($params);
        $encrypted = $filter->filter($json);

        return strtr((base64_encode($encrypted)), '+/=', '-_,');
    }


}

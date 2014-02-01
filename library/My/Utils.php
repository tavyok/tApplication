<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 12/7/13
 * Time: 3:05 PM
 */

class My_Utils {


    protected $name;

    protected $_data = array();

    public function __construct($name){
        $this->name = $name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }


    public function __set($name, $value){
        $this->_data[$name] = $value;
    }

    public function __get($name){
        if( array_key_exists($name, $this->_data)){
            return $this->_data[$name];
        }
        return null;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 12/7/13
 * Time: 3:05 PM
 */

class My_Utils {


    protected $name;

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


    static public function getHello(){
        return "hello";
    }

} 
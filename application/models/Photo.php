<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 12/7/13
 * Time: 4:15 PM
 */

class Model_Photo extends Zend_Db_Table_Row_Abstract {

    /*
    CREATE TABLE photo (
      id int(11) NOT NULL AUTO_INCREMENT,
      user_id int(11) NOT NULL,
      name varchar(32) NOT NULL,
      orig_name varchar(128) DEFAULT NULL,
      PRIMARY KEY (id)
    ) ENGINE=InnoDB;
    */


    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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

    /**
     * @param mixed $orig_name
     * @return $this
     */
    public function setOrigName($orig_name)
    {
        $this->orig_name = $orig_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrigName()
    {
        return $this->orig_name;
    }

    /**
     * @param mixed $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 12/7/13
 * Time: 4:15 PM
 */

class Model_User extends Zend_Db_Table_Row_Abstract {

    /*
        CREATE TABLE user (
            id int(11) NOT NULL AUTO_INCREMENT,
            username varchar(24) NOT NULL,
            email varchar(60) NOT NULL,
            pass char(32) NOT NULL,
            first_name varchar(40) NOT NULL,
            last_name varchar(40) NOT NULL,
            phone varchar(20) DEFAULT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY ukEmail (email)
        ) ENGINE=InnoDB
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
     * @param mixed $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $first_name
     * @return $this
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $last_name
     * @return $this
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $pass
     * @return $this
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function getFullName()
    {
        return $this->getFirstName() . ", " . $this->getLastName();
    }

    public function setAll($username,$email,$password,$firstname,$lastname,$phone)
    {
        $this->username=$username;
        $this->email=$email;
        $this->pass=$password;
        $this->first_name=$firstname;
        $this->last_name=$lastname;
        $this->phone=$phone;
        return $this;
    }

}
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
            password char(32) NOT NULL,
            first_name varchar(40) NOT NULL,
            last_name varchar(40) NOT NULL,
            phone varchar(20) DEFAULT NULL,
            role enum('user','admin') NOT NULL DEFAULT 'user',
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
     * @param mixed $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
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

    /**
     * @param mixed $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    public function setActivationCode($activation_code)
    {
        $this->activation_code = $activation_code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivationCode()
    {
        return $this->activation_code;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }
    /**
     * Calculated field
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . " " . $this->getLastName();
    }



}
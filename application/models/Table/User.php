<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 12/7/13
 * Time: 4:13 PM
 */

class Table_User extends Zend_Db_Table {

    protected $_name = "user";
    protected $_rowClass = "Model_User";

    const ROLE_GUEST = 'guest';
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * Get User BY Id
     *
     * @param $id
     * @return null|Model_User
     */
    public function getById($id)
    {
        $select = $this->select()
            ->where("id = ?", $id);

        return $this->fetchRow($select);

    }

    /**
     * Get User by Username
     *
     * @param $username
     * @return null| Model_User
     */
    public function getByUsername($username)
    {
        $select = $this->select()
            ->where("username = ?", $username);

        return $this->fetchRow($select);
    }

    /**
     * Get User by Email
     *
     * @param $email
     * @return null| Model_User
     */
    public function getByEmail($email)
    {
        $select = $this->select()
            ->where("email = ?", $email);

        return $this->fetchRow($select);
    }


    /**
     * Delete rows using list of ids
     *
     * @param array $cb
     */
    public function deleteByIds(array $cb)
    {
        $where = $this->getAdapter()->quoteInto("id in (?)",$cb);
        Zend_Debug::dump($where);
        $this->delete($where);
    }

    /**
     * Return all records from table
     *
     * @param null $name
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getAll($name = null)
    {
        $select = $this->select();
        
        if( ! is_null( $name ) ){
            $select->where('username like ','%' . $name . '%');
        }

        return $this->fetchAll($select);
    }


    /**
     * Folosit in autentificare cu user/password
     *
     * @param $email
     * @param $password
     * @return null|Model_User
     */
    public function authenticate($email, $password)
    {
        $select = $this->select()
            ->where("email = ?",$email)
            ->where("password = ?", md5($password) );

        return $this->fetchRow($select);
    }


} 
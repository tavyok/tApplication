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

    public function init(){
        $this->config = Zend_Registry::get('__CONFIG__');
    }


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

    public function getByPhoto($photo)
    {
        $select = $this->select()
            ->where("photo = ?", $photo);

        return $this->fetchRow($select);
    }

    /**
     * Delete rows using list of ids
     *
     * @param array $cb
     */
    public function deleteByIds(array $cb)
    {
        $tableuser=new Table_User();
        foreach($cb as &$id)
        {
        $user=$tableuser->getById($id);
        $photo=$user->getPhoto();

        $uploadFolder = realpath( $this->config['upload']['folder'] );


        if ($photo!="")
            if( file_exists($uploadFolder."/".$photo)){

                unlink($uploadFolder."/".$photo);
                }
        }

        $where = $this->getAdapter()->quoteInto("id in (?)",$cb);
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

    public function getPhotos()
    {
        $select = $this->fetchAll( $select = $this->select())->toArray();
        $photos=array();
        for ($i=0;$i<count($select);$i++)
             $photos[] = $select[$i]["photo"];
        return $photos;

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
            ->where("username = ?",$email)
            ->where("password = ?", md5($password) );

        if (is_null($select))
        {
            $select = $this->select()
                ->where("email = ?",$email)
                ->where("password = ?", $password );

        }
        return $this->fetchRow($select);
    }


    public function getByEmailPassword( $email, $password )
    {
        $select = $this->select()
            ->where("email = ?",$email)
            ->where("password = ?", md5($password) );

        if (is_null($this->fetchRow($select)))
        {
            $select = $this->select()
                ->where("email = ?",$email)
                ->where("password = ?", $password );

        }

        return $this->fetchRow($select);
    }

    public function getByUsernamePassword( $username, $password )
    {
        $select = $this->select()
            ->where("username = ?", $username )
            ->where("password = ?", md5($password) );

        if (is_null($select))
        {
            $select = $this->select()
                ->where("username = ?",$username)
                ->where("password = ?", $password );

        }

        return $this->fetchRow($select);
    }


    /**
     * @param $username
     * @param $activationCode
     * @return null|Model_User
     */
    public function getByUsernameActivationCode($username, $activationCode)
    {
        $select = $this->select()
            ->where("username = ?", $username)
            ->where("activation_code = ?", $activationCode );

        return $this->fetchRow($select);

    }


} 
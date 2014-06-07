<?php
/**
 * Created by PhpStorm.
 * User: NicolaeSergiu
 * Date: 12/7/13
 * Time: 4:13 PM
 */

class Table_Photo extends Zend_Db_Table {

    protected $_name = "photo";
    protected $_rowClass = "Model_Photo";

    protected $config;

    public function __construct($config = array(), $definition = null){
        parent::__construct($config, $definition);

        $this->config = Zend_Registry::get('__CONFIG__');
    }


    /**
     * Get User BY Id
     *
     * @param $id
     * @return null|Model_Photo
     */
    public function getById($id)
    {
        $select = $this->select()
            ->where("id = ?", $id);

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
    * @param null $userId
    * @return Zend_Db_Table_Rowset_Abstract
    */
    public function getAll($userId = null)
    {
        $select = $this->select();
        
        if( ! is_null( $userId ) ){
            $select->where('user_id = ?',$userId );
        }

        return $this->fetchAll($select);
    }


} 
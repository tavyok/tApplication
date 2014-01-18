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

} 
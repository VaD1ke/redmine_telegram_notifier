<?php
namespace App\Model\Entity;

use App\DB\Adapter\Connect;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\SqlInterface;

/**
 * Entity collection
 *
 * @category   App
 * @package    App
 * @subpackage DB
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Collection
{
    /**
     * Database connection
     *
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $_adapter;
    /**
     * Sql
     *
     * @var Sql
     */
    protected $_sql;
    /**
     * Sql object
     *
     * @var SqlInterface
     */
    protected $_sqlObject;

    /**
     * Object initialization
     *
     * @param Connect $connect Connect
     * @param Sql     $sql     Sql
     */
    public function __construct(Connect $connect, Sql $sql)
    {
        $this->_adapter = $connect->getAdapter();
        $this->_sql     = $sql;
    }

    /**
     * Load
     *
     * @param string  $tableName  Table name
     * @param string  $primaryKey Primary key
     * @param integer $id         ID
     *
     * @return $this
     */
    public function load($tableName, $primaryKey, $id)
    {
        $this->_sqlObject = $this->_sql->select();
        $this->_sqlObject->from($tableName)->where([$primaryKey => $id]);

        return $this;
    }

    /**
     * Add
     *
     * @param string $tableName Table name
     * @param array  $data      Data
     *
     * @return void
     */
    public function add($tableName, array $data)
    {
        $insert = $this->_sql->insert();
        $insert->into($tableName)->values($data);
        $this->_executeSql($this->_sql, $insert);
    }

    /**
     * Update
     *
     * @param string  $tableName  Table name
     * @param string  $primaryKey Primary key
     * @param integer $id         Chat ID
     * @param array   $data       Data
     *
     * @return void
     */
    public function update($tableName, $primaryKey, $id, $data)
    {
        $update = $this->_sql->update();
        $update->table($tableName)->set($data)->where([$primaryKey => $id]);

        $this->_executeSql($this->_sql, $update);
    }

    /**
     * Delete
     *
     * @param string  $tableName  Table name
     * @param string  $primaryKey Primary key
     * @param integer $id         Chat ID
     *
     * @return void
     */
    public function delete($tableName, $primaryKey = null, $id = null)
    {
        $delete = $this->_sql->delete();
        $delete->from($tableName);

        if ($primaryKey && $id) {
            $delete->where([$primaryKey => $id]);
        }
        $this->_executeSql($this->_sql, $delete);
    }

    /**
     * Get first row
     *
     * @param string $tableName Table name
     *
     * @return $this
     */
    public function loadFirstRow($tableName)
    {
        $this->_sqlObject = $this->_sql->select($tableName);
        $this->_sqlObject->limit(1);

        return $this;
    }

    /**
     * Get select
     *
     * @return \Zend\Db\Sql\Select
     */
    public function getSelect()
    {
        return $this->_sql->select();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->_executeSql($this->_sql, $this->_sqlObject)->toArray();
    }

    /**
     * Get last generated value
     *
     * @return mixed
     */
    public function getLastInsertedValue()
    {
        return $this->_adapter->getDriver()->getLastGeneratedValue();
    }


    /**
     * Execute sql
     *
     * @param Sql          $sql       Sql
     * @param SqlInterface $sqlObject Sql object
     *
     * @return \Zend\Db\Adapter\Driver\StatementInterface|\Zend\Db\ResultSet\ResultSet
     */
    protected function _executeSql(Sql $sql, SqlInterface $sqlObject)
    {
        $sqlString = $sql->buildSqlString($sqlObject);
        return $this->_adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
    }
}

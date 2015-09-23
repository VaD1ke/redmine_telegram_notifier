<?php
namespace App\DB\Adapter;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\SqlInterface;

/**
 * Database provider
 *
 * @category   App
 * @package    App
 * @subpackage DB
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Provider
{
    /**
     * Database connection
     *
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $_adapter;
    /**
     * Connect
     *
     * @var Connect
     */
    protected $_connect;
    /**
     * Sql
     *
     * @var Sql
     */
    protected $_sql;

    /**
     * Object initialization
     *
     * @param Connect $connect Connect
     * @param Sql     $sql     Sql
     */
    public function __construct(Connect $connect, Sql $sql)
    {
        $this->_connect = $connect;
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
     * @return array
     */
    public function load($tableName, $primaryKey, $id)
    {
        $select = $this->_sql->select();
        $select->from($tableName)->where([$primaryKey => $id]);

        $results = $this->_executeSql($this->_sql, $select);

        return $results->toArray();
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
     * @return array
     */
    public function loadFirstRow($tableName)
    {
        $select = $this->_sql->select($tableName);
        $select->limit(1);

        return $this->_executeSql($this->_sql, $select)->toArray();
    }


    /**
     * Get last update
     *
     * @return null|string
     */
    public function loadLastUpdate()
    {
        $select = $this->_sql->select('last_update');
        $select->columns(['update_id'])->limit(1);

        return $this->_executeSql($this->_sql, $select)->toArray();
    }

    /**
     * Set last update
     *
     * @param string $updateId Update ID
     *
     * @return void
     */
    public function updateLastUpdate($updateId)
    {
        $delete = $this->_sql->delete();
        $delete->from('last_update');
        $this->_executeSql($this->_sql, $delete);

        $insert = $this->_sql->insert();
        $insert->into('last_update')->values(['update_id' => $updateId]);
        $this->_executeSql($this->_sql, $insert);
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

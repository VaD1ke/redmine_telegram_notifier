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
     * Object initialization
     */
    public function __construct()
    {
        $connect = new Connect();
        $this->_adapter = $connect->getAdapter();
    }

    /**
     * Load chat
     *
     * @param integer $id ID
     *
     * @return mixed
     */
    public function loadChat($id)
    {
        $sql = new Sql($this->_adapter);
        $select = $sql->select();
        $select->from('chat')->where(['chat_id' => $id]);

        $results = $this->_executeSql($sql, $select);

        return $results->toArray();
    }

    /**
     * Add chat
     *
     * @param integer $id        Chat ID
     * @param string  $chatName  Chat name
     * @param string  $redmineId Redmine ID
     *
     * @return void
     */
    public function addChat($id, $chatName, $redmineId)
    {
        $sql = new Sql($this->_adapter);
        $insert = $sql->insert();
        $insert->into('chat')->values([
            'chat_id'    => $id,
            'name'       => $chatName,
            'redmine_id' => $redmineId,
        ]);
        $this->_executeSql($sql, $insert);
    }

    /**
     * Update chat
     *
     * @param integer $id        Chat ID
     * @param string  $chatName  Chat name
     * @param string  $redmineId Redmine ID
     *
     * @return void
     */
    public function updateChat($id, $chatName, $redmineId)
    {
        $sql = new Sql($this->_adapter);

        $update = $sql->update();
        $update->table('chat')->set([
            'name'       => $chatName,
            'redmine_id' => $redmineId,
        ])->where(['chat_id' => $id]);

        $this->_executeSql($sql, $update);
    }

    /**
     * Delete chat
     *
     * @param integer $id Chat ID
     *
     * @return void
     */
    public function deleteChat($id)
    {
        $sql = new Sql($this->_adapter);
        $delete = $sql->delete();
        $delete->from('chat')->where(['chat_id' => $id]);
        $this->_executeSql($sql, $delete);
    }

    /**
     * Get last update
     *
     * @return null|string
     */
    public function loadLastUpdate()
    {
        $sql = new Sql($this->_adapter);
        $select = $sql->select('last_update');
        $select->columns(['update_id'])->limit(1);

        return $this->_executeSql($sql, $select)->toArray();
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
        $sql = new Sql($this->_adapter);
        $delete = $sql->delete();
        $delete->from('last_update');
        $this->_executeSql($sql, $delete);

        $insert = $sql->insert();
        $insert->into('last_update')->values(['update_id' => $updateId]);
        $this->_executeSql($sql, $insert);
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

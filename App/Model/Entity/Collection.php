<?php
namespace App\Model\Entity;

use App\DB\Adapter\Connect;
use App\Model\Entity;
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
     * @param Entity $entity Entity
     *
     * @return $this
     */
    public function load(Entity $entity)
    {
        $this->_sqlObject = $this->getSelect();
        $this->_sqlObject
            ->from($entity->getTableName())
            ->where([$entity->getPrimaryKey() => $entity->getId()]);

        return $this;
    }

    /**
     * Load all
     *
     * @param Entity $entity Entity
     *
     * @return $this
     */
    public function loadAll(Entity $entity)
    {
        $this->_sqlObject = $this->getSelect();
        $this->_sqlObject->from($entity->getTableName());

        return $this;
    }

    /**
     * Add
     *
     * @param Entity $entity Entity
     *
     * @return void
     */
    public function add(Entity $entity)
    {
        $insert = $this->_sql->insert();
        $insert->into($entity->getTableName())->values($entity->getData());
        $this->_executeSql($this->_sql, $insert);
    }

    /**
     * Update
     *
     * @param Entity $entity Entity
     *
     * @return void
     */
    public function update(Entity $entity)
    {
        $update = $this->_sql->update();
        $update->table($entity->getTableName())
            ->set($entity->getData())
            ->where([$entity->getPrimaryKey() => $entity->getId()]);

        $this->_executeSql($this->_sql, $update);
    }

    /**
     * Delete
     *
     * @param Entity $entity Entity
     *
     * @return void
     */
    public function delete(Entity $entity)
    {
        $delete = $this->_sql->delete();
        $delete->from($entity->getTableName());

        if ($entity->getPrimaryKey() && $entity->getId()) {
            $delete->where([$entity->getPrimaryKey() => $entity->getId()]);
        }
        $this->_executeSql($this->_sql, $delete);
    }

    /**
     * Get first row
     *
     * @param Entity $entity Entity
     *
     * @return $this
     */
    public function loadFirstRow(Entity $entity)
    {
        $this->_sqlObject = $this->_sql->select($entity->getTableName());
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

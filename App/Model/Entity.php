<?php
namespace App\Model;

use App\DB\Adapter\Provider as Adapter;

/**
 * Entity abstract
 *
 * @category   App
 * @package    App
 * @subpackage Model
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
abstract class Entity
{
    /**
     * Table name
     *
     * @var string
     */
    protected $_tableName;
    /**
     * Primary key
     *
     * @var string
     */
    protected $_primaryKey = 'id';
    /**
     * Data
     *
     * @var array
     */
    protected $_data = [];

    /**
     * Database adapter
     *
     * @var Adapter
     */
    private $_adapter;

    /**
     * Object initialization
     *
     * @param Adapter $adapter DB adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * Load
     *
     * @return array
     */
    public function load()
    {
        $loaded = null;
        if (array_key_exists($this->_primaryKey, $this->_data) && $this->getId()) {
            $loaded = $this->_adapter->load($this->_tableName, $this->_primaryKey, $this->getId());
        } else {
            $loaded = $this->_adapter->loadFirstRow($this->_tableName);
        }

        return reset($loaded);
    }

    /**
     * Save
     *
     * @return $this
     */
    public function save()
    {
        $loaded = $this->load();

        if (array_key_exists($this->_primaryKey, $loaded) && $loaded[$this->_primaryKey]) {
            $this->_adapter->update($this->_tableName, $this->_primaryKey, $this->getId(), $this->_data);
        } else {
            $this->_adapter->add($this->_tableName, $this->_data);
        }

        return $this;
    }

    /**
     * Delete
     *
     * @return $this
     */
    public function delete()
    {
        $this->_adapter->delete($this->_tableName, $this->_primaryKey, $this->getId());

        return $this;
    }


    /**
     * Set ID
     *
     * @param number $id ID
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->_data[$this->_primaryKey] = $id;
        return $this;
    }

    /**
     * Get ID
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->_data[$this->_primaryKey];
    }

    /**
     * Set data
     *
     * @param array $data Data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->_data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }
}

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
     * Collection
     *
     * @var Entity\Collection
     */
    protected $_collection;

    /**
     * Object initialization
     *
     * @param Adapter           $adapter    DB adapter
     * @param Entity\Collection $collection Collection
     */
    public function __construct(Entity\Collection $collection)
    {
        $this->_collection = $collection;
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
            $loaded = $this->_collection->load($this->_tableName, $this->_primaryKey, $this->getId())->getData();
        } else {
            $loaded = $this->_collection->loadFirstRow($this->_tableName)->getData();
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
            $this->_collection->update($this->_tableName, $this->_primaryKey, $this->getId(), $this->_data);
        } else {
            $this->_collection->add($this->_tableName, $this->_data);
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
        $this->_collection->delete($this->_tableName, $this->_primaryKey, $this->getId());

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

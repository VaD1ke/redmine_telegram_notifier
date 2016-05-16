<?php
namespace App\Model;

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
        if ($this->_isIdExist()) {
            return reset($this->_collection->load($this)->getData());
        }

        return null;
    }

    /**
     * Save
     *
     * @return $this
     */
    public function save()
    {
        if ($this->_isRowExist()) {
            $this->_collection->update($this);
        } else {
            $this->_collection->add($this);
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
        $this->_collection->delete($this);

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

    /**
     * Get primary key
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->_tableName;
    }

    /**
     * Get collection
     *
     * @return Entity\Collection
     */
    public function getCollection()
    {
        return $this->_collection;
    }


    /**
     * Is ID exist
     *
     * @return bool
     */
    protected function _isIdExist()
    {
        return array_key_exists($this->_primaryKey, $this->_data) && $this->getId();
    }

    /**
     * Is row exist
     *
     * @return bool
     */
    protected function _isRowExist()
    {
        $loaded = $this->load();

        return $loaded && array_key_exists($this->_primaryKey, $loaded) && $loaded[$this->_primaryKey];
    }
}

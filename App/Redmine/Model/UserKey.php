<?php
namespace App\Redmine\Model;

use \App\Model\Entity as EntityAbstract;

/**
 * Redmine user key entity model
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class UserKey extends EntityAbstract
{
    /**
     * Column key
     */
    const COLUMN_KEY = 'key';

    /**
     * Table name
     *
     * @var string
     */
    protected $_tableName = 'redmine_user_key';

    /**
     * Load by key
     *
     * @return array
     */
    public function loadByKey()
    {
        return $this->_collection->getByKey($this)->getData();
    }

    public function deleteByKey()
    {
        return $this->_collection->deleteByKey($this);
    }

    /**
     * Set key
     *
     * @param string $key Key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->_data[self::COLUMN_KEY] = $key;
        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->_data[self::COLUMN_KEY];
    }
}

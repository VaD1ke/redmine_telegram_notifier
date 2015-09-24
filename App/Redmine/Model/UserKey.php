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
     * Collection
     *
     * @var UserKey\Collection
     */
    protected $_collection;


    /**
     * Object initialization
     *
     * @param UserKey\Collection $collection Collection
     */
    public function __construct(UserKey\Collection $collection)
    {
        parent::__construct($collection);
    }

    /**
     * Load by key
     *
     * @return array
     */
    public function loadByKey()
    {
        return $this->_collection
            ->getByKey($this->_tableName, self::COLUMN_KEY, $this->getKeyId())
            ->getData();
    }

    /**
     * Set key ID
     *
     * @param string $keyId Key ID
     *
     * @return $this
     */
    public function setKeyId($keyId)
    {
        $this->_data[self::COLUMN_KEY] = $keyId;
        return $this;
    }

    /**
     * Get key ID
     *
     * @return string
     */
    public function getKeyId()
    {
        return $this->_data[self::COLUMN_KEY];
    }
}

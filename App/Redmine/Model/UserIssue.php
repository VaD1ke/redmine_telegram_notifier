<?php
namespace App\Redmine\Model;

use \App\Model\Entity as EntityAbstract;

/**
 * Redmine user issue entity model
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class UserIssue extends EntityAbstract
{
    /**
     * Key ID column name
     */
    const COLUMN_KEY_ID = 'key_id';
    /**
     * Issue ID column name
     */
    const COLUMN_ISSUE_ID = 'issue_id';

    /**
     * Table name
     *
     * @var string
     */
    protected $_tableName = 'redmine_user_issue';
    /**
     * Collection
     *
     * @var UserIssue\Collection
     */
    protected $_collection;


    /**
     * Object initialization
     *
     * @param UserIssue\Collection $collection Collection
     */
    public function __construct(UserIssue\Collection $collection)
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
        return $this->_collection->getByKey($this)->getData();
    }
    /**
     * Delete by key
     *
     * @return $this
     */
    public function deleteByKey()
    {
        return $this->_collection->deleteByKey($this);
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
        $this->_data[self::COLUMN_KEY_ID] = $keyId;
        return $this;
    }
    /**
     * Get key ID
     *
     * @return string
     */
    public function getKeyId()
    {
        return $this->_data[self::COLUMN_KEY_ID];
    }
}

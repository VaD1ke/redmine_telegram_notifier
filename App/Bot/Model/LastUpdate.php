<?php
namespace App\Bot\Model;

use \App\Model\Entity as EntityAbstract;

/**
 * Last update entity model
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class LastUpdate extends EntityAbstract
{
    /**
     * Update column name
     */
    const UPDATE_COLUMN_NAME = 'update_id';

    /**
     * Table name
     *
     * @var string
     */
    protected $_tableName = 'last_update';
    /**
     * Primary key
     *
     * @var null
     */
    protected $_primaryKey = null;


    /**
     * Get update id
     *
     * @return mixed
     */
    public function getBotUpdateId()
    {
        $lastUpdate = $this->getCollection()->loadFirstRow($this)->getData();

        if (!$lastUpdate) {
            return null;
        }

        return reset($lastUpdate)[self::UPDATE_COLUMN_NAME];
    }

    /**
     * Set update id
     *
     * @return void
     */
    public function saveBotUpdateId()
    {
        $this->delete();
        $this->save();
    }
}

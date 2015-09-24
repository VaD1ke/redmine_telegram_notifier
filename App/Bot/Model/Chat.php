<?php
namespace App\Bot\Model;

use \App\Model\Entity as EntityAbstract;

/**
 * Chat entity model
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Chat extends EntityAbstract
{
    /**
     * Chat ID column name
     */
    const COLUMN_CHAT_ID = 'chat_id';
    /**
     * Chat name column name
     */
    const COLUMN_CHAT_NAME = 'chat_name';
    /**
     * Redmine key ID column name
     */
    const COLUMN_REDMINE_KEY_ID = 'redmine_key_id';

    /**
     * Table name
     *
     * @var string
     */
    protected $_tableName = 'bot_chat';
    /**
     * Primary key
     *
     * @var string
     */
    protected $_primaryKey = 'chat_id';

    /**
     * Object initialization
     *
     * @param Chat\Collection $collection Collection
     */
    public function __construct(Chat\Collection $collection)
    {
        parent::__construct($collection);
    }


}

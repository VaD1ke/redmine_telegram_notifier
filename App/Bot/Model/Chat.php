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
     * Table name
     *
     * @var string
     */
    protected $_tableName = 'chat';
    /**
     * Primary key
     *
     * @var string
     */
    protected $_primaryKey = 'chat_id';
}

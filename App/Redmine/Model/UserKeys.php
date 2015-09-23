<?php
namespace App\Redmine\Model;

use \App\Model\Entity as EntityAbstract;

/**
 * Chat entity model
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class UserKeys extends EntityAbstract
{
    /**
     * Table name
     *
     * @var string
     */
    protected $_tableName = 'redmine_user_keys';
}

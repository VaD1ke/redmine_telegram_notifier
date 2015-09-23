<?php
namespace App\Bot\Model;

use \App\Model\Entity\Entity as EntityAbstract;

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
     * Table name
     *
     * @var string
     */
    protected $_tableName = 'last_update';
}

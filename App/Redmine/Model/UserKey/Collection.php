<?php
namespace App\Redmine\Model\UserKey;

/**
 * User key collection
 *
 * @category   App
 * @package    App
 * @subpackage DB
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Collection extends \App\Model\Entity\Collection
{
    /**
     * Get by key
     *
     * @param string  $tableName  Table name
     * @param string  $keyColumn  Key column
     * @param string  $key        Key
     *
     * @return $this
     */
    public function getByKey($tableName, $keyColumn, $key)
    {
        $this->_sqlObject = $this->getSelect();
        $this->_sqlObject->from($tableName)->where([$keyColumn => $key]);

        return $this;
    }
}

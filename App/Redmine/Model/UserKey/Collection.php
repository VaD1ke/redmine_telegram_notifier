<?php
namespace App\Redmine\Model\UserKey;

use App\Redmine\Model\UserKey;

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
     * @param UserKey $entity Entity
     *
     * @return $this
     */
    public function getByKey(UserKey $entity)
    {
        $this->_sqlObject = $this->getSelect();
        $this->_sqlObject->from($entity->getTableName())
            ->where([UserKey::COLUMN_KEY => $entity->getKeyId()]);

        return $this;
    }

    /**
     * Delete by key
     *
     * @param UserKey $entity User key entity
     *
     * @return $this
     */
    public function deleteByKey(UserKey $entity)
    {
        $delete = $this->_sql->delete();
        $delete->from($entity->getTableName())
            ->where([UserKey::COLUMN_KEY => $entity->getKeyId()]);

        $this->_executeSql($this->_sql, $delete);
    }
}

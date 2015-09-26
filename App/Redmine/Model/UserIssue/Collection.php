<?php
namespace App\Redmine\Model\UserIssue;

use App\Redmine\Model\UserIssue;

/**
 * User issue collection
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
     * @param UserIssue $entity Entity
     *
     * @return $this
     */
    public function getByKey(UserIssue $entity)
    {
        $this->_sqlObject = $this->getSelect();
        $this->_sqlObject->from($entity->getTableName())
            ->where([UserIssue::COLUMN_KEY_ID => $entity->getKeyId()]);

        return $this;
    }

    /**
     * Delete by key
     *
     * @param UserIssue $entity User key entity
     *
     * @return $this
     */
    public function deleteByKey(UserIssue $entity)
    {
        $delete = $this->_sql->delete();
        $delete->from($entity->getTableName())
            ->where([UserIssue::COLUMN_KEY_ID => $entity->getKeyId()]);

        $this->_executeSql($this->_sql, $delete);
    }
}

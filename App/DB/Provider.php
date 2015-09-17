<?php
namespace App\DB;
require_once 'Connect.php';

use PDO;

/**
 * Database provider
 *
 * @category   App
 * @package    App
 * @subpackage DB
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Provider
{
    /**
     * Database connection
     *
     * @var PDO
     */
    protected $_db;

    /**
     * Object initialization
     */
    public function __construct()
    {
        $connect = new Connect();
        $this->_db = $connect->connectDatabase();
    }

    /**
     * Load chat
     *
     * @param integer $id ID
     *
     * @return mixed
     */
    public function loadChat($id)
    {
        $select = "SELECT * FROM chat WHERE chat_id=:chatId";
        $sth = $this->_db->prepare($select);
        $sth->bindValue(':chatId', $id, SQLITE3_INTEGER);
        $sth->execute();
        $a = $sth->fetch();

        return $a;
    }

    /**
     * Add chat
     *
     * @param integer $id        Chat ID
     * @param string  $chatName  Chat name
     * @param string  $redmineId Redmine ID
     *
     * @return void
     */
    public function addChat($id, $chatName, $redmineId)
    {
        $query = "INSERT INTO chat(chat_id, name, redmine_id) VALUES (:chatId, :name, :redmineId)";
        $sth = $this->_db->prepare($query);
        $sth->bindValue(':chatId', $id, SQLITE3_INTEGER);
        $sth->bindValue(':name', $chatName, SQLITE3_TEXT);
        $sth->bindValue(':redmineId', $redmineId, SQLITE3_TEXT);
        $sth->execute();
    }

    /**
     * Update chat
     *
     * @param integer $id        Chat ID
     * @param string  $chatName  Chat name
     * @param string  $redmineId Redmine ID
     *
     * @return void
     */
    public function updateChat($id, $chatName, $redmineId)
    {
        $query = "UPDATE chat SET name=:name, redmine_id=:redmineId WHERE chat_id=:chatId";
        $sth = $this->_db->prepare($query);
        $sth->bindValue(':chatId', $id, SQLITE3_INTEGER);
        $sth->bindValue(':name', $chatName, SQLITE3_TEXT);
        $sth->bindValue(':redmineId', $redmineId, SQLITE3_TEXT);
        $sth->execute();
    }

    /**
     * Delete chat
     *
     * @param integer $id Chat ID
     *
     * @return void
     */
    public function deleteChat($id)
    {
        $query = "DELETE FROM chat WHERE chat_id=:chatId";
        $sth = $this->_db->prepare($query);
        $sth->bindValue(':chatId', $id, SQLITE3_INTEGER);
        $sth->execute();
    }

    /**
     * Get last update
     *
     * @return null|string
     */
    public function loadLastUpdate()
    {
        $sth = $this->_db->prepare("SELECT update_id FROM last_update");
        $sth->execute();
        $a = $sth->fetch();

        return $a['update_id'];
    }

    /**
     * Set last update
     *
     * @param string $updateId Update ID
     *
     * @return void
     */
    public function updateLastUpdate($updateId)
    {
        $this->_db->exec("DELETE FROM last_update");
        $sth = $this->_db->prepare("INSERT INTO last_update (update_id) VALUES (:updateId)");
        $sth->bindValue(':updateId', $updateId, SQLITE3_TEXT);
        $sth->execute();
    }
}

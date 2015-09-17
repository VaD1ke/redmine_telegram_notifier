<?php
namespace App\DB;

use PDO;
use PDOException;

/**
 * Database connect
 *
 * @category   App
 * @package    App
 * @subpackage DB
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Connect
{
    /**
     * Database connection
     *
     * @var PDO
     */
    private static $_db;

    /**
     * Connect database
     *
     * @return bool|PDO
     */
    public static function connectDatabase()
    {
        if (self::$_db) {
            return self::$_db;
        }

        $dbName = __DIR__ . "/../../Database/notifier.db";

        try {
            self::$_db = new PDO("sqlite:$dbName");
            self::$_db->setAttribute(PDO::ATTR_TIMEOUT,55);
            self::$_db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$_db->exec('PRAGMA journal_mode=WAL;');
            return self::$_db;
        } catch(PDOException $e) {
            return false;
        }
    }
}

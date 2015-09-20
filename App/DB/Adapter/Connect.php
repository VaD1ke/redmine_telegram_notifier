<?php
namespace App\DB\Adapter;

use \Zend\Db\Adapter\Adapter;

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
    private static $_adapter;

    /**
     * Connect database
     *
     * @return bool|PDO
     */
    public static function getAdapter()
    {
        if (self::$_adapter) {
            return self::$_adapter;
        }

        $dbName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
                 . '..' . DIRECTORY_SEPARATOR . "Database/notifier.db";

        self::$_adapter = new Adapter([
            'driver'   => 'Pdo_Sqlite',
            'database' => $dbName
        ]);

        return self::$_adapter;
    }
}

<?php
namespace App;

/**
 * Application
 *
 * @category   App
 * @package    App
 * @subpackage App
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class App
{
    /**
     * Run
     *
     * @return mixed
     */
    public function run()
    {
        $updater = new Bot\Updater();
        $updater->checkUpdates();
    }

    /**
     * Get class instance
     *
     * @param string $className
     * @param array $constructArguments
     *
     * @return mixed
     */
    public static function getClassInstance($className, $constructArguments = [])
    {
        if (class_exists($className)) {
            return new $className($constructArguments);
        }

        return false;
    }
}

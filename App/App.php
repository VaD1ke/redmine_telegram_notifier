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
     * Dependency injection
     *
     * @var \Zend\Di\Di
     */
    private $_di;

    /**
     * Object initialization
     *
     * @param \Zend\Di\Di $di
     */
    public function __construct(\Zend\Di\Di $di)
    {
        $this->_di = $di;
    }

    /**
     * Run
     *
     * @return mixed
     */
    public function run()
    {
        $dic = new Model\DiC($this->_di);
        $dic->assemble();

        /** @var \App\Bot\Updater $updater */
        $updater = $this->_di->get('BotUpdater');
        $updater->checkUpdates();
    }
}

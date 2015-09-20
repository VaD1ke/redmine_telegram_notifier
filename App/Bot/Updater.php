<?php
namespace App\Bot;

use \App\Config\Parser as ConfigParser;

/**
 * Bot updater
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Updater
{
    /**
     * Telegram notifier bot
     *
     * @var Api
     */
    protected $_botApi;

    /**
     * Check updates
     *
     * @return void
     */
    public function checkUpdates()
    {
        $this->_botApi = new Api(new ConfigParser());

        $helperData = new Data\Provider();
        $updates    = json_decode($this->_botApi->getUpdates($helperData->getBotUpdateId()), true);

        $updatesHandler = new Handler();
        $updatesHandler->handleBotApiUpdates($updates, $this->_botApi);
    }
}
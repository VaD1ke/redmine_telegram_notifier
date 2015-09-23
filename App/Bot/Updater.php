<?php
namespace App\Bot;

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
     * Data provider
     *
     * @var Data\Provider
     */
    protected $_dataProvider;
    /**
     * Updates handler
     *
     * @var Handler
     */
    protected $_updatesHandler;

    /**
     * Object initialization
     *
     * @param Api           $api            Bot API
     * @param Data\Provider $dataProvider   Data provider
     * @param Handler       $updatesHandler Updates handler
     */
    public function __construct(Api $api, Data\Provider $dataProvider, Handler $updatesHandler)
    {
        $this->_botApi         = $api;
        $this->_dataProvider   = $dataProvider;
        $this->_updatesHandler = $updatesHandler;
    }

    /**
     * Check updates
     *
     * @return void
     */
    public function checkUpdates()
    {
        $updates = json_decode(
            $this->_botApi->getUpdates($this->_dataProvider->getBotUpdateId()), $assoc = true
        );

        $this->_updatesHandler->handleBotApiUpdates($updates);
    }
}
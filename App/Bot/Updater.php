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
     * Last update
     *
     * @var Model\LastUpdate
     */
    protected $_lastUpdate;
    /**
     * Updates handler
     *
     * @var Handler
     */
    protected $_updatesHandler;

    /**
     * Object initialization
     *
     * @param Api              $api            Bot API
     * @param Model\LastUpdate $lastUpdate     Last update
     * @param Handler          $updatesHandler Updates handler
     */
    public function __construct(Api $api, Model\LastUpdate $lastUpdate, Handler $updatesHandler)
    {
        $this->_botApi         = $api;
        $this->_lastUpdate     = $lastUpdate;
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
            $this->_botApi->getUpdates($this->_lastUpdate->getBotUpdateId()), $assoc = true
        );

        $this->_updatesHandler->handleBotApiUpdates($updates);
    }
}

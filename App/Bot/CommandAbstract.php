<?php
namespace App\Bot;

use \App\Bot\Api as BotApi;

/**
 * Command abstract
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
abstract class CommandAbstract
{
    /**
     * Bot api
     *
     * @var BotApi
     */
    protected $_botApi;

    /**
     * Object initialization
     *
     * @param BotApi $botApi Bot API
     */
    public function __construct(BotApi $botApi)
    {
        $this->_botApi = $botApi;
    }

    /**
     * Notify
     *
     * @param integer $chatId  Chat ID
     * @param string  $message Message
     *
     * @return void
     */
    protected function _notify($chatId, $message)
    {
        $this->_botApi->sendMessage($chatId, $message);
    }
}

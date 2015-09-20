<?php
namespace App\Bot\Command;

use \App\Bot\ICommand;
use \App\Bot\Api as BotApi;
use \App\DB\Adapter\Provider as DbProvider;
use \App\Bot\Helper\Update as HelperUpdate;

/**
 * Deregister command
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Deregister implements ICommand
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
     * Execute command
     *
     * @param array $update Update
     *
     * @return void
     */
    public function execute(array $update)
    {
        $subscriber = $this->_deleteSubscriber($update);

        if ($subscriber['success']) {
            $message = $this->_getUnsubscribeMessage($subscriber['name']);
        } else {
            $message = $this->_getNotSubscribedMessage($subscriber['name']);
        }

        $this->_botApi->sendMessage($subscriber['chat_id'], $message);
    }


    /**
     * Delete subscriber
     *
     * @param array $update Update
     *
     * @return array
     */
    protected function _deleteSubscriber(array $update)
    {
        $updateHelper  = new HelperUpdate();

        $chatId   = $updateHelper->getChatId($update);
        $chatName = $updateHelper->getChatName($update);
        $chatData = [ 'chat_id' => $chatId, 'name' => $chatName ];

        $provider = new DbProvider();

        $chat = $provider->loadChat($chatId);
        if ($chat) {
            $provider->deleteChat($chatId);
            $chatData['success'] = true;
        } else {
            $chatData['success'] = false;
        }

        return $chatData;
    }

    /**
     * Get unsubscribe message
     *
     * @param string $chatName Chat name
     *
     * @return string
     */
    protected function _getUnsubscribeMessage($chatName)
    {
        $message = $chatName . ', ' . 'Вы успешно отписались от уведомлений!';
        return $message;
    }

    /**
     * Get not subscribed message
     *
     * @param string $chatName Chat name
     *
     * @return string
     */
    protected function _getNotSubscribedMessage($chatName)
    {
        $message = $chatName . ', ' . 'Вы еще не подписались на уведомления!';
        return $message;
    }
}

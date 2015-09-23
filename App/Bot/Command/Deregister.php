<?php
namespace App\Bot\Command;

use App\Bot\ICommand;
use App\Bot\CommandAbstract;
use App\Bot\Api as BotApi;
use App\Bot\Model\Chat;
use App\Bot\Helper\Update as HelperUpdate;

/**
 * Deregister command
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Deregister extends CommandAbstract implements ICommand
{
    /**
     * Update helper
     *
     * @var HelperUpdate
     */
    protected $_updateHelper;
    /**
     * Chat
     *
     * @var Chat
     */
    protected $_chat;

    /**
     * Object initialization
     *
     * @param BotApi        $botApi        Bot API
     * @param HelperUpdate  $updateHelper  Update helper
     * @param Chat          $chat          Chat
     */
    public function __construct(BotApi $botApi, HelperUpdate $updateHelper, Chat $chat)
    {
        parent::__construct($botApi);

        $this->_updateHelper = $updateHelper;
        $this->_chat         = $chat;
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

        $this->_notify($subscriber['chat_id'], $message);
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
        $chatId   = $this->_updateHelper->getChatId($update);
        $chatName = $this->_updateHelper->getChatName($update);
        $chatData = [ 'chat_id' => $chatId, 'name' => $chatName ];

        $chat = $this->_chat->setId($chatId)->load();
        if ($chat) {
            $this->_chat->delete();
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

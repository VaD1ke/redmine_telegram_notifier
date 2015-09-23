<?php
namespace App\Bot\Command;

use App\Bot\ICommand;
use App\Bot\CommandAbstract;
use App\Bot\Model\Chat;
use App\Bot\Api as BotApi;
use App\Bot\Helper\Message as HelperMessage;
use App\Bot\Helper\Update as HelperUpdate;

/**
 * Register command
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Register extends CommandAbstract implements ICommand
{
    /**
     * Update helper
     *
     * @var HelperUpdate
     */
    protected $_updateHelper;
    /**
     * Message helper
     *
     * @var HelperMessage
     */
    protected $_messageHelper;
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
     * @param helperMessage $messageHelper Message helper
     * @param Chat          $chat          Chat
     */
    public function __construct(
        BotApi $botApi, HelperUpdate $updateHelper, HelperMessage $messageHelper, Chat $chat
    ) {
        parent::__construct($botApi);

        $this->_updateHelper  = $updateHelper;
        $this->_messageHelper = $messageHelper;
        $this->_chat          = $chat;
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
        $subscriber = $this->_addSubscriber($update);

        if (!$subscriber) {
            return;
        }

        $this->_notify($subscriber['chat_id'], $this->_getSubscribeMessage($subscriber['name']));
    }


    /**
     * Add subscriber
     *
     * @param array $update Update
     *
     * @return array
     */
    protected function _addSubscriber(array $update)
    {
        $chatData   = [];
        $redmineKey = $this->_messageHelper->getArgumentFromMessage($this->_updateHelper->getMessageText($update));

        if (!$redmineKey) {
            return $chatData;
        }

        $chatData = [
            'chat_id'    => $this->_updateHelper->getChatId($update),
            'name'       => $this->_updateHelper->getChatName($update),
            'redmine_id' => $redmineKey
        ];

        $this->_chat->setData($chatData)->save();

        return $chatData;
    }

    /**
     * Get sign up message
     *
     * @param string $chatName Chat name
     *
     * @return string
     */
    protected function _getSubscribeMessage($chatName)
    {
        $message = $chatName . ', ' . 'Вы успешно подписались на уведомления с Redmine!';
        return $message;
    }

    /**
     * Save Redmine user total issues
     *
     * @param string $redmineUser Redmine user
     *
     * @return void
     */
    protected function _saveRedmineUserTotalIssues($redmineUser)
    {

    }
}

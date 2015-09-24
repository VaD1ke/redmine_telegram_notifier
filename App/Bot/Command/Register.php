<?php
namespace App\Bot\Command;

use App\Bot\ICommand;
use App\Bot\CommandAbstract;
use App\Bot\Model\Chat;
use App\Redmine\Model\UserKey;
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
     * Chat entity
     *
     * @var Chat
     */
    protected $_chat;
    /**
     * User key entity
     *
     * @var UserKey
     */
    protected $_userKey;

    /**
     * Object initialization
     *
     * @param BotApi        $botApi        Bot API
     * @param HelperUpdate  $updateHelper  Update helper
     * @param helperMessage $messageHelper Message helper
     * @param Chat          $chat          Chat
     * @param UserKey       $userKey       Redmine user key
     */
    public function __construct(
        BotApi $botApi, HelperUpdate $updateHelper, HelperMessage $messageHelper,
        Chat $chat, UserKey $userKey
    ) {
        parent::__construct($botApi);

        $this->_updateHelper  = $updateHelper;
        $this->_messageHelper = $messageHelper;
        $this->_chat          = $chat;
        $this->_userKey       = $userKey;
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
        $subscriber = $this->_getSubscriber($update);
        if ($subscriber) {
            $this->_addRedmineKey($update, $subscriber[Chat::COLUMN_REDMINE_KEY_ID]);
        } else {
            $keyId      = $this->_addRedmineKey($update);
            $subscriber = $this->_addSubscriber($update, $keyId);
        }

        if (!$subscriber) {
            return;
        }

        $this->_notify(
            $subscriber[Chat::COLUMN_CHAT_ID], $this->_getSubscribeMessage($subscriber[Chat::COLUMN_CHAT_NAME])
        );
    }


    /**
     * Add redmine key
     *
     * @param array  $update Update
     * @param number $id     ID
     *
     * @return array
     */
    protected function _addRedmineKey(array $update, $id = null)
    {
        $redmineKey = $this->_messageHelper->getArgumentFromMessage(
            $this->_updateHelper->getMessageText($update)
        );

        if ($id) {
            $this->_userKey->setId($id);
        }

        $this->_userKey->setKeyId($redmineKey)->save();
        return $this->_userKey->getCollection()->getLastInsertedValue();
    }

    /**
     * Add subscriber
     *
     * @param array  $update Update
     * @param number $keyId  Redmine key ID
     *
     * @return array
     */
    protected function _addSubscriber(array $update, $keyId = null)
    {
        $chatData   = [];

        if (!$keyId) {
            return $chatData;
        }

        $chatData = [
            Chat::COLUMN_CHAT_ID        => $this->_updateHelper->getChatId($update),
            Chat::COLUMN_CHAT_NAME      => $this->_updateHelper->getChatName($update),
            Chat::COLUMN_REDMINE_KEY_ID => $keyId,
        ];

        $this->_chat->setData($chatData)->save();

        return $chatData;
    }

    /**
     * Get subscriber
     *
     * @param array $update Update
     *
     * @return array
     */
    protected function _getSubscriber(array $update)
    {
        return $this->_chat->setId($this->_updateHelper->getChatId($update))->load();
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
}

<?php
namespace App\Bot\Command;

use App\Bot\ICommand;
use App\Bot\CommandAbstract;
use App\Bot\Model\Chat;
use App\Redmine\Model\UserKey;
use App\Redmine\Model\UserIssue;
use App\Bot\Api as BotApi;
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
     * User key entity
     *
     * @var UserKey
     */
    protected $_userKey;
    /**
     * User issue entity
     *
     * @var UserKey
     */
    protected $_userIssue;

    /**
     * Object initialization
     *
     * @param BotApi        $botApi        Bot API
     * @param HelperUpdate  $updateHelper  Update helper
     * @param Chat          $chat          Chat
     * @param UserKey       $userKey       Redmine user key
     * @param UserIssue     $userIssue     User issue
     */
    public function __construct(
        BotApi $botApi, HelperUpdate $updateHelper, Chat $chat,
        UserKey $userKey, UserIssue $userIssue
    ) {
        parent::__construct($botApi);

        $this->_updateHelper = $updateHelper;
        $this->_chat         = $chat;
        $this->_userKey      = $userKey;
        $this->_userIssue    = $userIssue;
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
            $this->_deleteRedmineUserIssues($subscriber[Chat::COLUMN_REDMINE_KEY_ID]);
            $this->_deleteRedmineKey($subscriber[Chat::COLUMN_REDMINE_KEY_ID]);
            $message = $this->_getUnsubscribeMessage($subscriber[Chat::COLUMN_CHAT_NAME]);
        } else {
            $message = $this->_getNotSubscribedMessage($subscriber[Chat::COLUMN_CHAT_NAME]);
        }

        $this->_notify($subscriber[Chat::COLUMN_CHAT_ID], $message);
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
        $chatData = [ Chat::COLUMN_CHAT_ID => $chatId, Chat::COLUMN_CHAT_NAME => $chatName ];

        $chat = $this->_chat->setId($chatId)->load();
        if ($chat) {
            $this->_chat->delete();
            $chatData['success'] = true;
            $chatData[Chat::COLUMN_REDMINE_KEY_ID] = $chat[Chat::COLUMN_REDMINE_KEY_ID];
        } else {
            $chatData['success'] = false;
        }

        return $chatData;
    }

    /**
     * Delete redmine key
     *
     * @param integer $id ID
     *
     * @return void
     */
    protected function _deleteRedmineKey($id)
    {
        $this->_userKey->setId($id)->delete();
    }

    /**
     * Delete Redmine user issues
     *
     * @param number $keyId  Redmine key ID
     */
    protected function _deleteRedmineUserIssues($keyId)
    {
        $this->_userIssue->setKeyId($keyId)->deleteByKey();
    }


    /**
     * Get unsubscribe message
     *
     * @param string $chatName Chat name
     *
     * @return string
     */
    private function _getUnsubscribeMessage($chatName)
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
    private function _getNotSubscribedMessage($chatName)
    {
        $message = $chatName . ', ' . 'Вы еще не подписались на уведомления!';
        return $message;
    }
}

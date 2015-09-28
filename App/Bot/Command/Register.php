<?php
namespace App\Bot\Command;

use App\Bot\ICommand;
use App\Bot\CommandAbstract;
use App\Bot\Model\Chat;
use App\Redmine\Model\UserIssue;
use App\Redmine\Model\UserKey;
use App\Bot\Api as BotApi;
use App\Bot\Helper\Message as HelperMessage;
use App\Bot\Helper\Update as HelperUpdate;
use App\Redmine\Issue\Getter as IssueGetter;
use App\Redmine\Helper\Issue as HelperIssue;

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
     * User issue entity
     *
     * @var UserIssue
     */
    protected $_userIssue;
    /**
     * Redmine user issue getter
     *
     * @var IssueGetter
     */
    protected $_issueGetter;
    /**
     * Redmine user issue getter
     *
     * @var IssueGetter
     */
    protected $_issueHelper;

    /**
     * Redmine user api key value
     *
     * @var string
     */
    protected $_redmineKeyValue;

    /**
     * Object initialization
     *
     * @param BotApi        $botApi        Bot API
     * @param HelperUpdate  $updateHelper  Update helper
     * @param helperMessage $messageHelper Message helper
     * @param Chat          $chat          Chat
     * @param UserKey       $userKey       Redmine user key
     * @param UserIssue     $userIssue     User issue
     * @param IssueGetter   $issueGetter   Issue getter
     * @param HelperIssue   $issueHelper   Issue helper
     */
    public function __construct(
        BotApi $botApi, HelperUpdate $updateHelper, HelperMessage $messageHelper,
        Chat $chat, UserKey $userKey, UserIssue $userIssue,
        IssueGetter $issueGetter, HelperIssue $issueHelper
    ) {
        parent::__construct($botApi);

        $this->_updateHelper  = $updateHelper;
        $this->_messageHelper = $messageHelper;
        $this->_chat          = $chat;
        $this->_userKey       = $userKey;
        $this->_issueGetter   = $issueGetter;
        $this->_issueHelper   = $issueHelper;
        $this->_userIssue     = $userIssue;
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
        $this->_redmineKeyValue = $this->_getRedmineKeyFromUpdate($update);

        $subscriber = $this->_getSubscriber($update);

        if ($subscriber) {
            $keyId = $subscriber[Chat::COLUMN_REDMINE_KEY_ID];
            $this->_addRedmineKey($keyId);
        } else {
            $keyId      = $this->_addRedmineKey();
            $subscriber = $this->_addSubscriber($update, $keyId);
        }

        if (!$subscriber) {
            return;
        }

        $issues = $this->_getRedmineUserIssues();
        $this->_deleteRedmineUserIssues($keyId);
        $this->_addRedmineUserIssues($issues, $keyId);

        $this->_notify(
            $subscriber[Chat::COLUMN_CHAT_ID], $this->_getSubscribeMessage($subscriber[Chat::COLUMN_CHAT_NAME])
        );
    }


    /**
     * Add redmine key
     *
     * @param number $id ID
     *
     * @return mixed
     */
    protected function _addRedmineKey($id = null)
    {
        if ($id) {
            $this->_userKey->setId($id);
        }

        $this->_userKey->setKey($this->_redmineKeyValue)->save();
        return $this->_userKey->getCollection()->getLastInsertedValue();
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
     * Get subscriber redmine issues
     *
     * @return mixed
     */
    protected function _getSubscriberRedmineIssues()
    {
        return $this->_issueGetter->setRedmineKey($this->_redmineKeyValue)->getIssues();
    }

    /**
     * Get redmine API key from update
     *
     * @param array $update Update
     *
     * @return bool|string
     */
    protected function _getRedmineKeyFromUpdate(array $update)
    {
        return $this->_messageHelper->getArgumentFromMessage(
            $this->_updateHelper->getMessageText($update)
        );
    }

    /**
     * Get Redmine user issues
     *
     * @return mixed
     */
    protected function _getRedmineUserIssues()
    {
        return $this->_issueGetter->setRedmineKey($this->_redmineKeyValue)->getIssues();
    }

    /**
     * Add Redmine user issues
     *
     * @param array  $issues Issues
     * @param number $keyId  Redmine key ID
     *
     * @return void
     */
    protected function _addRedmineUserIssues(array $issues, $keyId)
    {
        foreach ($issues as $issue) {
            $issueData = [
                UserIssue::COLUMN_KEY_ID   => $keyId,
                UserIssue::COLUMN_ISSUE_ID => $this->_issueHelper->getIssueId($issue),
            ];
            $this->_userIssue->setData($issueData)->save();
        }
    }

    /**
     * Delete Redmine user issues
     *
     * @param number $keyId Redmine key ID
     *
     * @return void
     */
    protected function _deleteRedmineUserIssues($keyId)
    {
        $this->_userIssue->setKeyId($keyId)->deleteByKey();
    }


    /**
     * Get sign up message
     *
     * @param string $chatName Chat name
     *
     * @return string
     */
    private function _getSubscribeMessage($chatName)
    {
        $message = $chatName . ', ' . 'Вы успешно подписались на уведомления с Redmine!';
        return $message;
    }
}

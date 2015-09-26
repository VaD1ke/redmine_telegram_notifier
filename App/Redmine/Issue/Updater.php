<?php
namespace App\Redmine\Issue;

use App\Bot\Api as  BotApi;
use App\Bot\Model\Chat;
use App\Redmine\Issue\Getter as IssueGetter;
use App\Redmine\Helper\Issue as IssueHelper;
use App\Redmine\Model\UserIssue;
use App\Redmine\Model\UserKey;

/**
 * Redmine user issue updater
 *
 * @category   App
 * @package    App
 * @subpackage Redmine
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Updater
{
    /**
     * Chat entity
     *
     * @var Chat
     */
    protected $_chat;
    /**
     * User issue entity
     *
     * @var UserIssue
     */
    protected $_userIssue;
    /**
     * User redmine API key entity
     *
     * @var UserKey
     */
    protected $_userKey;
    /**
     * Issue getter
     *
     * @var Getter
     */
    protected $_issueGetter;
    /**
     * Issue helper
     *
     * @var IssueHelper
     */
    protected $_issueHelper;

    /**
     * Object initialization
     *
     * @param Chat        $chat        Chat
     * @param UserIssue   $userIssue   User issue
     * @param UserKey     $userKey     User key
     * @param Getter      $issueGetter Issue getter
     * @param IssueHelper $issueHelper Issue helper
     * @param BotApi      $botApi      Bot API
     */
    public function __construct(
        Chat $chat, UserIssue $userIssue, UserKey $userKey,
        IssueGetter $issueGetter, IssueHelper $issueHelper , BotApi $botApi
    ) {
        $this->_chat        = $chat;
        $this->_userIssue   = $userIssue;
        $this->_userKey     = $userKey;
        $this->_issueGetter = $issueGetter;
        $this->_issueHelper = $issueHelper;
    }

    /**
     * Update user issues
     *
     * @return void
     */
    public function updateIssues()
    {
        $users = $this->_chat->getCollection()->loadAll($this->_chat)->getData();

        foreach ($users as $user) {
            $key = $this->_userKey->setId($user[Chat::COLUMN_REDMINE_KEY_ID])->load();

            $redmineIssues = $this->_issueGetter
                ->setRedmineKey($key[UserKey::COLUMN_KEY])
                ->setIsOnlyNumbers(true)
                ->getIssues();

            $currentIssues = $this->_userIssue->setKeyId($user[Chat::COLUMN_REDMINE_KEY_ID])->loadByKey();

            $newIssues = $this->_getNewIssues($redmineIssues, $currentIssues);

        }
    }

    /**
     * Save redmine issues
     *
     * @param number $userId        User ID
     * @param array  $redmineIssues Redmine issue
     */
    protected function _saveRedmineIssues($userId, array $redmineIssues)
    {
        foreach ($redmineIssues as $issue) {

        }
    }

    /**
     * Get new issues
     *
     * @param array $redmineIssues Redmine issues
     * @param array $currentIssues Current issues
     *
     * @return array
     */
    protected function _getNewIssues(array $redmineIssues, array $currentIssues)
    {
        $currentIssues = array_column($currentIssues, UserIssue::COLUMN_ISSUE_ID);

        return array_diff($redmineIssues, $currentIssues);
    }
}

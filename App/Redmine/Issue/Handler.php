<?php
namespace App\Redmine\Issue;

use App\Bot\Api as BotApi;
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
class Handler
{
    /**
     * User data
     *
     * @var array
     */
    protected $_userData;
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
     * Bot API
     *
     * @var BotApi
     */
    protected $_botApi;

    /**
     * Object initialization
     *
     * @param UserIssue   $userIssue   User issue
     * @param UserKey     $userKey     User key
     * @param Getter      $issueGetter Issue getter
     * @param IssueHelper $issueHelper Issue helper
     * @param BotApi      $botApi      Bot API
     */
    public function __construct(
        UserIssue $userIssue, UserKey $userKey, BotApi $botApi,
        IssueGetter $issueGetter, IssueHelper $issueHelper
    ) {
        $this->_userIssue   = $userIssue;
        $this->_userKey     = $userKey;
        $this->_botApi      = $botApi;
        $this->_issueGetter = $issueGetter;
        $this->_issueHelper = $issueHelper;
    }

    public function handleUserIssues()
    {
        $user = $this->getUserData();

        if (!$user) {
            return;
        }

        $key = $this->_userKey->setId($user[Chat::COLUMN_REDMINE_KEY_ID])->load();

        $redmineIssues = $this->_getRedmineIssues($key[UserKey::COLUMN_KEY]);

        $redmineIssueNumbers = $this->_issueHelper->getIssueNumbers($redmineIssues);
        $currentIssueNumbers = $this->_getIssueNumbers($this->_getCurrentIssues());

        $newIssueNumbers = $this->_getNewIssueNumbers($redmineIssueNumbers, $currentIssueNumbers);

        if (!$newIssueNumbers) {
            return;
        }

        $keyId = $key[$this->_userKey->getPrimaryKey()];
        $this->_deleteIssuesByKey($keyId);
        $this->_saveIssues($keyId, $redmineIssueNumbers);

        $newIssuesData = $this->_getIssuesDataByNumbers($newIssueNumbers, $redmineIssues);

        foreach ($newIssuesData as $newIssue) {
            $this->_botApi->sendMessage($user[Chat::COLUMN_CHAT_ID], $this->_getMessageForNewIssue($newIssue));
        }
    }

    /**
     * Get user data
     *
     * @return array
     */
    public function getUserData()
    {
        return $this->_userData;
    }
    /**
     * Set user data
     *
     * @param array $userData User data
     *
     * @return $this
     */
    public function setUserData(array $userData)
    {
        $this->_userData = $userData;
        return $this;
    }


    /**
     * Get Redmine issues
     *
     * @param string $apiKey API key
     *
     * @return array
     */
    protected function _getRedmineIssues($apiKey)
    {
        return $this->_issueGetter->setRedmineKey($apiKey)->getIssues();
    }

    /**
     * Get current issues
     *
     * @return array
     */
    protected function _getCurrentIssues()
    {
        $user = $this->getUserData();
        return $this->_userIssue->setKeyId($user[Chat::COLUMN_REDMINE_KEY_ID])->loadByKey();
    }

    /**
     * Get issue numbers
     *
     * @param array $issues Issue
     *
     * @return array
     */
    protected function _getIssueNumbers(array $issues)
    {
        return array_column($issues, UserIssue::COLUMN_ISSUE_ID);
    }

    /**
     * Get new issue numbers
     *
     * @param array $redmineIssues Redmine issues
     * @param array $currentIssues Current issues
     *
     * @return array
     */
    protected function _getNewIssueNumbers(array $redmineIssues, array $currentIssues)
    {
        return array_diff($redmineIssues, $currentIssues);
    }

    /**
     * Save issues
     *
     * @param number $keyId  Key ID
     * @param array  $issues Issues
     *
     * @return void
     */
    protected function _saveIssues($keyId, array $issues)
    {
        foreach($issues as $issue) {
            $this->_userIssue->setData([
                UserIssue::COLUMN_KEY_ID   => $keyId,
                UserIssue::COLUMN_ISSUE_ID => $issue,
            ])->save();
        }
    }

    /**
     * Delete issues by key
     *
     * @param string $keyId Key ID
     *
     * @return void
     */
    protected function _deleteIssuesByKey($keyId)
    {
        $this->_userIssue->setKeyId($keyId)->deleteByKey();
    }

    /**
     * Get issues data by numbers
     *
     * @param array $issueNumbers Issue numbers
     * @param array $issues       Issues data
     *
     * @return array
     */
    protected function _getIssuesDataByNumbers(array $issueNumbers, array $issues)
    {
        $issuesData = [];

        foreach ($issueNumbers as $issueNumber) {
            foreach ($issues as $issue) {
                if ($this->_issueHelper->getIssueId($issue) != $issueNumber) {
                    continue;
                }
                $issuesData[] = [
                    'project' => $this->_issueHelper->getProjectName($issue),
                    'subject' => $this->_issueHelper->getIssueSubject($issue),
                    'author'  => $this->_issueHelper->getAuthorName($issue),
                    'number'  => $issueNumber,
                ];
                break;
            }
        }

        return $issuesData;
    }


    /**
     * Get message for new issue
     *
     * @param array $issueData Issue data
     *
     * @return string
     */
    private function _getMessageForNewIssue(array $issueData)
    {
        return "На Вас была переведена задача\n{$this->_issueGetter->getRedmineUrl($issueData['number'])}\n"
                . "Тема: '{$issueData['subject']}'\n"
                . "Создана {$issueData['author']} на проекте {$issueData['project']}";
    }
}

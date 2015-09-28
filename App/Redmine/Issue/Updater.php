<?php
namespace App\Redmine\Issue;

use App\Bot\Model\Chat;
use App\Redmine\Issue\Handler as IssueHandler;
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
     * Issue handler
     *
     * @var IssueHandler
     */
    protected $_issueHandler;

    /**
     * Object initialization
     *
     * @param Chat         $chat         Chat
     * @param IssueHandler $issueHandler Issue handler
     */
    public function __construct(Chat $chat, IssueHandler $issueHandler)
    {
        $this->_chat        = $chat;
        $this->_issueHandler = $issueHandler;
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
            $this->_issueHandler->setUserData($user)->handleUserIssues();
        }
    }
}

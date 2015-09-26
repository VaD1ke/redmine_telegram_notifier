<?php
namespace App\Redmine;

use App\Redmine\Issue\Updater as IssueUpdater;

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
     * @var IssueUpdater
     */
    protected $_issueUpdater;

    /**
     * Object initialization
     *
     * @param IssueUpdater $issueUpdater Issue updater
     */
    public function __construct(IssueUpdater $issueUpdater)
    {
        $this->_issueUpdater = $issueUpdater;
    }

    /**
     * Check Redmine updates
     *
     * @return void
     */
    public function checkUpdates()
    {
        $this->_issueUpdater->updateIssues();
    }
}

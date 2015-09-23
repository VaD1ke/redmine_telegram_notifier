<?php
namespace App\Redmine\Helper;

/**
 * Redmine API issue helper
 *
 * @category   App
 * @package    App
 * @subpackage Redmine
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Issue
{
    /**
     * Get total count
     *
     * @param array $issuesData Issues data
     *
     * @return mixed
     */
    public function getTotalCount(array $issuesData)
    {
        return $issuesData['total_count'];
    }

    /**
     * Get project name
     *
     * @param array $issue Issue
     *
     * @return mixed
     */
    public function getProjectName(array $issue)
    {
        return $issue['project']['name'];
    }

    /**
     * Get author name
     *
     * @param array $issue Issue
     *
     * @return mixed
     */
    public function getAuthorName(array $issue)
    {
        return $issue['author']['name'];
    }

    /**
     * Get issue ID
     *
     * @param array $issue Issue
     *
     * @return mixed
     */
    public function getIssueId(array $issue)
    {
        return $issue['id'];
    }
}

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
}

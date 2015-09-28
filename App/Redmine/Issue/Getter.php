<?php
namespace App\Redmine\Issue;

use App\Redmine\Api as RedmineApi;
use App\Redmine\Helper\Issue as IssueHelper;

/**
 * Redmine user issue getter
 *
 * @category   App
 * @package    App
 * @subpackage Bot
 * @author     Vladislav Slesarenko <vslesarenko@oggettoweb.com>
 */
class Getter
{
    /**
     * Redmine API
     *
     * @var RedmineApi
     */
    protected $_redmineApi;
    /**
     * Issue helper
     *
     * @var IssueHelper
     */
    protected $_issueHelper;

    /**
     * Redmine key
     *
     * @var string
     */
    protected $_redmineKey;

    /**
     * Object initialization
     *
     * @param RedmineApi  $redmineApi  Redmine API
     * @param IssueHelper $issueHelper Issue helper
     */
    public function __construct(RedmineApi $redmineApi, IssueHelper $issueHelper)
    {
        $this->_redmineApi  = $redmineApi;
        $this->_issueHelper = $issueHelper;
    }

    /**
     * Get issues
     *
     * @return array
     */
    public function getIssues()
    {
        $issues = json_decode(
            $this->_redmineApi->setApiKey($this->getRedmineKey())->getIssues(), $assoc = true
        );

        if (array_key_exists('issues', $issues) && $issues['issues']) {
            return $issues['issues'];
        }

        return [];
    }

    /**
     * Get Redmine IRL
     *
     * @param number $issueNumber Issue number
     *
     * @return string
     */
    public function getRedmineUrl($issueNumber = null)
    {
        if ($issueNumber) {
            return $this->_redmineApi->getUrl() . 'issues/' . $issueNumber;
        }
        return $this->_redmineApi->getUrl();
    }


    /**
     * Set redmine key
     *
     * @param string $redmineKey Redmine key
     *
     * @return $this
     */
    public function setRedmineKey($redmineKey)
    {
        $this->_redmineKey = $redmineKey;
        return $this;
    }
    /**
     * Get redmine key
     *
     * @return string|null
     */
    public function getRedmineKey()
    {
        return $this->_redmineKey;
    }
}
